<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use App\Services\Billing\InvoiceService;
use App\Services\Billing\NylonPayGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User { return User::factory()->create(['role' => 'admin']); }
    private function data(): array
    {
        return ['client_name' => 'Jane Client', 'client_email' => 'jane@example.com', 'client_business' => 'Acme',
            'title' => 'Website development', 'currency' => 'UGX', 'items' => [
                ['description' => 'Design', 'quantity' => 2, 'unit_price' => 10000],
                ['description' => 'Hosting', 'quantity' => 1, 'unit_price' => 5000],
            ]];
    }
    private function invoice(): Invoice { return app(InvoiceService::class)->save($this->data(), $this->admin()->id); }
    private function issued(): Invoice { return app(InvoiceService::class)->issue($this->invoice()); }

    public function test_invoice_admin_and_api_access_are_guarded()
    {
        $this->get('/admin/invoices')->assertRedirect('/login');
        $this->getJson('/api/v1/admin/invoices')->assertUnauthorized();
        $this->actingAs(User::factory()->create(['role' => 'user']))->postJson('/api/v1/admin/invoices', $this->data())->assertForbidden();
    }

    public function test_admin_creates_draft_with_server_totals_then_issues_share_link()
    {
        $this->actingAs($this->admin())->post('/admin/invoices', $this->data() + ['total' => 1, 'status' => 'paid'])->assertRedirect();
        $invoice = Invoice::firstOrFail();
        $this->assertSame(25000, $invoice->total);
        $this->assertSame('draft', $invoice->status);
        $this->get($invoice->share_url)->assertNotFound();
        $this->get('/admin/invoices')->assertOk()->assertSee($invoice->number);
        $this->get('/admin/invoices/create')->assertOk();
        $this->get('/admin/invoices/'.$invoice->id)->assertOk();
        $this->get('/admin/invoices/'.$invoice->id.'/edit')->assertOk();
        $this->post('/admin/invoices/'.$invoice->id.'/issue')->assertRedirect();
        $this->get($invoice->share_url)->assertOk()->assertSee('25,000')->assertHeader('Referrer-Policy', 'no-referrer');
        $this->putJson('/admin/invoices/'.$invoice->id, $this->data())->assertStatus(409);
        $this->get('/invoices/not-a-real-token')->assertNotFound();
    }

    public function test_invalid_amounts_and_line_items_are_rejected()
    {
        $this->actingAs($this->admin());
        $data = $this->data(); $data['items'][0]['unit_price'] = '0.5';
        $this->postJson('/api/v1/admin/invoices', $data)->assertUnprocessable()->assertJsonValidationErrors('items.0.unit_price');
        $data['items'] = [['description' => 'Small', 'quantity' => 1, 'unit_price' => 499]];
        $this->postJson('/api/v1/admin/invoices', $data)->assertUnprocessable()->assertJsonValidationErrors('items');
        $data['items'][0]['unit_price'] = 1000000000; $data['items'][0]['quantity'] = 10000;
        $this->postJson('/api/v1/admin/invoices', $data)->assertUnprocessable();
        $data['items'] = [];
        $this->postJson('/api/v1/admin/invoices', $data)->assertUnprocessable();
        $this->assertDatabaseCount('invoices', 0);
    }

    public function test_sanctum_api_shares_invoice_workflow()
    {
        Sanctum::actingAs($this->admin(), ['invoices:manage']);
        $response = $this->postJson('/api/v1/admin/invoices', $this->data())->assertCreated()->assertJsonPath('data.total', 25000);
        $id = $response->json('data.id');
        $this->assertArrayNotHasKey('public_token', $response->json('data'));
        $this->postJson("/api/v1/admin/invoices/$id/issue")->assertOk()->assertJsonPath('data.status', 'issued');
        $this->getJson("/api/v1/admin/invoices/$id")->assertOk()->assertJsonCount(2, 'data.items');
        $this->getJson('/api/v1/admin/invoices?status=issued')->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_checkout_is_reused_and_redirect_does_not_mark_paid()
    {
        $invoice = $this->issued();
        $this->mock(NylonPayGateway::class, function ($mock) {
            $mock->shouldReceive('configured')->andReturn(true);
            $mock->shouldReceive('createCheckout')->once()->withArgs(fn ($invoice) => $invoice->total === 25000)->andReturn(['url' => 'https://pay.example.com/invoice/one', 'id' => 'provider-one']);
        });
        $this->post($invoice->share_url.'/pay', ['amount' => 1, 'phone' => '0771234567', 'confirm_phone' => 1])->assertRedirect('https://pay.example.com/invoice/one');
        $this->post($invoice->share_url.'/pay', ['phone' => '0771234567', 'confirm_phone' => 1])->assertRedirect('https://pay.example.com/invoice/one');
        $this->get($invoice->share_url.'?status=successful')->assertOk();
        $this->assertSame('issued', $invoice->fresh()->status);
        $this->actingAs($this->admin())->postJson('/admin/invoices/'.$invoice->id.'/void')->assertStatus(409);
    }

    public function test_checkout_timeout_cannot_create_duplicate_provider_invoices()
    {
        $invoice = $this->issued();
        $this->mock(NylonPayGateway::class, function ($mock) {
            $mock->shouldReceive('configured')->andReturn(true);
            $mock->shouldReceive('createCheckout')->once()->andThrow(new \RuntimeException('timeout'));
        });
        $this->post($invoice->share_url.'/pay', ['phone' => '0771234567', 'confirm_phone' => 1])->assertSessionHas('payment_error');
        $this->post($invoice->share_url.'/pay', ['phone' => '0771234567', 'confirm_phone' => 1])->assertSessionHas('payment_error');
        $this->assertSame('uncertain', $invoice->fresh()->checkout_state);
    }

    public function test_missing_configuration_does_not_claim_checkout_and_void_is_not_payable()
    {
        config(['nylonpay.api_key' => null, 'nylonpay.api_secret' => null]);
        $invoice = $this->issued();
        $this->post($invoice->share_url.'/pay', ['phone' => '0771234567', 'confirm_phone' => 1])->assertSessionHas('payment_error');
        $this->assertSame('not_started', $invoice->fresh()->checkout_state);
        app(InvoiceService::class)->void($invoice);
        $this->get($invoice->share_url)->assertOk()->assertSee('Invoice voided')->assertDontSee('Pay with Nylon Pay');
    }

    private function webhook(Invoice $invoice, array $changes = [], string $delivery = 'delivery-one', bool $valid = true, ?string $timestamp = null)
    {
        config(['nylonpay.webhook_secret' => 'test-webhook-secret']);
        $payload = array_merge(['reference' => $invoice->payment_reference, 'amount' => '25000.00', 'currency' => 'UGX', 'type' => 'charge', 'status' => 'successful'], $changes);
        $body = json_encode(['delivery_id' => $delivery, 'event' => 'transaction.'.$payload['status'], 'payload' => $payload, 'timestamp' => $timestamp ?? now()->toIso8601String()]);
        return $this->call('POST', '/api/v1/webhooks/nylonpay', [], [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json', 'HTTP_X_NYLON_SIGNATURE' => $valid ? hash_hmac('sha256', $body, 'test-webhook-secret') : 'invalid'], $body);
    }

    public function test_signed_webhooks_are_amount_checked_idempotent_and_cannot_regress_paid_status()
    {
        $invoice = $this->issued(); $invoice->update(['checkout_state' => 'ready']);
        $this->webhook($invoice, [], 'bad-signature', false)->assertUnauthorized();
        $this->webhook($invoice, [], 'stale', true, now()->subHour()->toIso8601String())->assertUnauthorized();
        $this->webhook($invoice, ['amount' => '1'])->assertUnprocessable();
        $this->webhook($invoice, ['currency' => 'USD'])->assertUnprocessable();
        $this->webhook($invoice, ['type' => 'payout'])->assertUnprocessable();
        $this->assertSame('issued', $invoice->fresh()->status);
        $this->webhook($invoice)->assertOk();
        $paidAt = $invoice->fresh()->paid_at->toIso8601String();
        $this->webhook($invoice)->assertOk();
        $this->webhook($invoice, ['status' => 'failed'], 'delivery-two')->assertOk();
        $this->assertSame('paid', $invoice->fresh()->status);
        $this->assertSame($paidAt, $invoice->fresh()->paid_at->toIso8601String());
        $this->assertDatabaseCount('invoice_webhook_deliveries', 2);
        $this->post($invoice->share_url.'/pay', ['phone' => '0771234567', 'confirm_phone' => 1])->assertSessionHas('payment_error');
    }

    public function test_reconciliation_confirms_payment_without_webhook()
    {
        $invoice = $this->issued(); $invoice->update(['checkout_state' => 'ready']);
        $this->mock(NylonPayGateway::class, function ($mock) use ($invoice) {
            $mock->shouldReceive('transaction')->once()->with($invoice->payment_reference)->andReturn([
                'reference' => $invoice->payment_reference, 'amount' => 25000, 'currency' => 'UGX', 'type' => 'charge', 'status' => 'successful',
            ]);
        });
        $this->artisan('invoices:reconcile')->assertExitCode(0);
        $this->assertSame('paid', $invoice->fresh()->status);
    }

    public function test_mobile_admin_can_create_use_and_revoke_scoped_token()
    {
        $admin = $this->admin();
        $admin->update(['password' => \Illuminate\Support\Facades\Hash::make('testing-password')]);
        $response = $this->postJson('/api/v1/auth/token', ['email' => $admin->email, 'password' => 'testing-password', 'device_name' => 'Test iPhone'])->assertOk();
        $token = $response->json('token');
        $this->withToken($token)->getJson('/api/v1/admin/invoices')->assertOk();
        $this->withToken($token)->deleteJson('/api/v1/auth/token')->assertNoContent();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_mobile_token_requires_admin_and_invoice_scope()
    {
        $user = User::factory()->create(['role' => 'user', 'password' => \Illuminate\Support\Facades\Hash::make('testing-password')]);
        $this->postJson('/api/v1/auth/token', ['email' => $user->email, 'password' => 'testing-password', 'device_name' => 'Test'])->assertUnprocessable();
        Sanctum::actingAs($this->admin(), ['other:read']);
        $this->getJson('/api/v1/admin/invoices')->assertForbidden();
    }

    public function test_verified_payment_issues_one_immutable_downloadable_receipt()
    {
        config(['nylonpay.business_name' => 'Original Business']);
        $invoice = $this->issued(); $invoice->update(['checkout_state' => 'ready']);
        $this->webhook($invoice)->assertOk();
        $receipt = $invoice->fresh()->receipt;
        $this->assertNotNull($receipt);
        $this->assertSame(25000, $receipt->details['total']);
        $this->webhook($invoice, [], 'receipt-repeat')->assertOk();
        $this->assertDatabaseCount('invoice_receipts', 1);
        config(['nylonpay.business_name' => 'Renamed Business']);
        $download = $this->get($invoice->share_url.'/receipt')->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')->assertHeader('Cache-Control', 'no-store, private');
        $this->assertStringStartsWith('%PDF-', $download->getContent());
        $this->assertStringContainsString($receipt->number.'.pdf', $download->headers->get('Content-Disposition'));
        $this->assertSame('Original Business', $receipt->fresh()->details['business_name']);
        $this->get($invoice->share_url)->assertOk()->assertSee('Download receipt')->assertSee('All settled.');
    }

    public function test_receipts_are_not_available_for_unpaid_or_unknown_invoices()
    {
        $invoice = $this->invoice();
        $this->get($invoice->share_url.'/receipt')->assertNotFound();
        app(InvoiceService::class)->issue($invoice);
        $this->get($invoice->share_url.'/receipt')->assertNotFound();
        $this->get('/invoices/unknown/receipt')->assertNotFound();
        $this->assertDatabaseCount('invoice_receipts', 0);
        $this->get('/admin/invoices/'.$invoice->id.'/receipt')->assertRedirect('/login');
    }

    public function test_admin_and_scoped_mobile_api_can_download_receipt()
    {
        $invoice = $this->issued(); $invoice->update(['checkout_state' => 'ready']);
        $this->webhook($invoice)->assertOk();
        $this->actingAs($this->admin())->get('/admin/invoices/'.$invoice->id.'/receipt')->assertOk()->assertHeader('Content-Type', 'application/pdf');
        Sanctum::actingAs($this->admin(), ['invoices:manage']);
        $this->getJson('/api/v1/admin/invoices/'.$invoice->id)->assertOk()->assertJsonPath('data.receipt_url', route('api.invoices.receipt', $invoice->id));
        $this->get('/api/v1/admin/invoices/'.$invoice->id.'/receipt')->assertOk()->assertHeader('Content-Type', 'application/pdf');
        Sanctum::actingAs($this->admin(), ['other:read']);
        $this->getJson('/api/v1/admin/invoices/'.$invoice->id.'/receipt')->assertForbidden();
    }

    public function test_admin_can_mark_invoice_paid_and_print_or_export_one_receipt()
    {
        $invoice = $this->invoice(); $admin = $this->admin();
        $data = ['payment_method'=>'cash', 'payment_reference'=>'CASH-01', 'payment_note'=>'Received at office', 'confirm_payment'=>1];
        $this->actingAs($admin)->post('/admin/invoices/'.$invoice->id.'/mark-paid', $data)->assertRedirect('/admin/invoices/'.$invoice->id);
        $invoice->refresh();
        $this->assertSame('paid', $invoice->status);
        $this->assertSame($admin->id, $invoice->marked_paid_by);
        $this->assertSame('Received at office', $invoice->manual_payment_note);
        $this->assertSame('Manual payment', $invoice->receipt->details['provider']);
        $this->assertSame('CASH-01', $invoice->receipt->details['payment_reference']);
        $number = $invoice->receipt->number;
        $this->post('/admin/invoices/'.$invoice->id.'/mark-paid', $data)->assertRedirect();
        $this->assertDatabaseCount('invoice_receipts', 1);
        $print = $this->get('/admin/invoices/'.$invoice->id.'/receipt?print=1')->assertOk()->assertHeader('Content-Type','application/pdf');
        $this->assertSame('inline; filename="'.$number.'.pdf"', $print->headers->get('Content-Disposition'));
        $this->get('/admin/invoices/'.$invoice->id.'/receipt')->assertOk()->assertHeader('Content-Disposition','attachment; filename="'.$number.'.pdf"');
        $html = view('invoices.receipt-pdf', ['receipt'=>$invoice->receipt,'details'=>$invoice->receipt->details])->render();
        $this->assertStringContainsString('Method: Cash', $html);
        $this->assertStringNotContainsString('Processed through Nylon Pay', $html);
        $this->assertStringNotContainsString('Received at office', $html);
        $this->get('/admin/invoices/'.$invoice->id)->assertOk()->assertSee('Print receipt');
    }

    public function test_manual_payment_requires_admin_confirmation_and_non_void_invoice()
    {
        $invoice = $this->issued();
        $data = ['payment_method'=>'bank_transfer','payment_note'=>'Bank receipt verified','confirm_payment'=>1];
        $this->post('/admin/invoices/'.$invoice->id.'/mark-paid',$data)->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role'=>'user']))->postJson('/admin/invoices/'.$invoice->id.'/mark-paid',$data)->assertForbidden();
        $this->actingAs($this->admin())->postJson('/admin/invoices/'.$invoice->id.'/mark-paid',array_merge($data,['confirm_payment'=>0]))->assertUnprocessable();
        app(InvoiceService::class)->void($invoice);
        $this->postJson('/admin/invoices/'.$invoice->id.'/mark-paid',$data)->assertStatus(409);
        $this->assertDatabaseCount('invoice_receipts', 0);
    }

    public function test_scoped_admin_api_can_record_manual_payment()
    {
        $invoice = $this->issued();
        Sanctum::actingAs($this->admin(), ['invoices:manage']);
        $this->postJson('/api/v1/admin/invoices/'.$invoice->id.'/mark-paid',[
            'payment_method'=>'mobile_money','payment_note'=>'Confirmed mobile transfer','confirm_payment'=>true,
        ])->assertOk()->assertJsonPath('data.status','paid');
        $this->assertDatabaseCount('invoice_receipts', 1);
    }

    public function test_payer_must_confirm_a_valid_mobile_number_before_checkout()
    {
        $invoice = $this->issued();
        $this->get($invoice->share_url.'/checkout')->assertOk()->assertSee('Confirm your number');
        $this->postJson($invoice->share_url.'/pay', ['phone'=>'0771234567'])->assertUnprocessable()->assertJsonValidationErrors('confirm_phone');
        $this->postJson($invoice->share_url.'/pay', ['phone'=>'12345','confirm_phone'=>true])->assertUnprocessable()->assertJsonValidationErrors('phone');
        $this->postJson($invoice->share_url.'/pay', ['phone'=>['0771234567'],'confirm_phone'=>true])->assertUnprocessable();
        $this->assertSame('not_started', $invoice->fresh()->checkout_state);
        $this->assertNull($invoice->fresh()->payer_phone);
    }

    public function test_confirmed_phone_is_normalized_and_cannot_change_an_existing_checkout()
    {
        $invoice = $this->issued(); $invoice->update(['client_phone'=>'+256701111111']);
        $this->mock(NylonPayGateway::class, function ($mock) {
            $mock->shouldReceive('configured')->andReturn(true);
            $mock->shouldReceive('createCheckout')->once()->withArgs(fn ($invoice) => $invoice->payer_phone === '+256771234567')
                ->andReturn(['url'=>'https://pay.example.com/confirmed','id'=>'confirmed']);
        });
        $this->post($invoice->share_url.'/pay',['phone'=>'077 123 4567','confirm_phone'=>1])->assertRedirect('https://pay.example.com/confirmed');
        $this->assertSame('+256771234567', $invoice->fresh()->payer_phone);
        $this->assertSame('+256701111111', $invoice->fresh()->client_phone);
        $this->post($invoice->share_url.'/pay',['phone'=>'+256701234567','confirm_phone'=>1])->assertSessionHas('payment_error');
        $this->assertSame('+256771234567', $invoice->fresh()->payer_phone);
        $this->post($invoice->share_url.'/pay',['phone'=>'256771234567','confirm_phone'=>1])->assertRedirect('https://pay.example.com/confirmed');
    }

    public function test_shared_invoice_has_absolute_preview_metadata_without_client_details()
    {
        $invoice = $this->issued();
        $response = $this->get($invoice->share_url)->assertOk();
        $dom = new \DOMDocument();
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $this->assertSame('website', $xpath->evaluate('string(//meta[@property="og:type"]/@content)'));
        $this->assertSame($invoice->share_url, $xpath->evaluate('string(//meta[@property="og:url"]/@content)'));
        $this->assertSame(asset('assets/img/social/invoice-preview.png'), $xpath->evaluate('string(//meta[@property="og:image"]/@content)'));
        $this->assertSame('summary_large_image', $xpath->evaluate('string(//meta[@name="twitter:card"]/@content)'));
        $description = $xpath->evaluate('string(//meta[@property="og:description"]/@content)');
        $this->assertStringNotContainsString($invoice->client_name, $description);
        $this->assertStringNotContainsString($invoice->client_email, $description);
        $this->assertSame('noindex,nofollow', $xpath->evaluate('string(//meta[@name="robots"]/@content)'));
        $size = getimagesize(public_path('assets/img/social/invoice-preview.png'));
        $this->assertSame([1200, 630], [$size[0], $size[1]]);
        $this->get($this->invoice()->share_url)->assertNotFound();
    }
}
