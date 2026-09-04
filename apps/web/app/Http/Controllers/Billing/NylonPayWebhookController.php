<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Billing\InvoicePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function NileSquad\NylonPay\verifyWebhookSignature;

class NylonPayWebhookController extends Controller
{
    public function __invoke(Request $request, InvoicePaymentService $payments)
    {
        $secret = config('nylonpay.webhook_secret');
        abort_unless(filled($secret) && verifyWebhookSignature([
            'payload' => $request->getContent(), 'signature' => $request->header('x-nylon-signature', ''), 'secret' => $secret,
        ]), 401);
        $body = json_decode($request->getContent(), true);
        $payload = $body['payload'] ?? [];
        if (!in_array($body['event'] ?? '', ['transaction.successful', 'transaction.failed', 'transaction.processing', 'transaction.cancelled'])) {
            return response()->json(['received' => true]);
        }
        abort_unless(is_string($body['delivery_id'] ?? null) && strlen($body['delivery_id']) <= 180, 422);
        $invoice = Invoice::where('payment_reference', $payload['reference'] ?? '')->first();
        // Other products can share this merchant key.
        if (!$invoice) { return response()->json(['received' => true]); }
        DB::transaction(function () use ($invoice, $body, $payload, $payments) {
            Invoice::whereKey($invoice->id)->lockForUpdate()->first();
            if (DB::table('invoice_webhook_deliveries')->where('delivery_id', $body['delivery_id'])->exists()) { return; }
            $payments->applyTransaction($invoice, $payload);
            DB::table('invoice_webhook_deliveries')->insert([
                'delivery_id' => $body['delivery_id'], 'invoice_id' => $invoice->id, 'received_at' => now(),
            ]);
        });
        return response()->json(['received' => true]);
    }
}
