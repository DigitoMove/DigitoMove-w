<?php
namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\InvoiceReceipt;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReceiptService
{
    public function issue(Invoice $invoice): InvoiceReceipt
    {
        return DB::transaction(function () use ($invoice) {
            $invoice = Invoice::lockForUpdate()->findOrFail($invoice->id);
            abort_unless($invoice->status === 'paid' && $invoice->paid_at, 404, 'A receipt is available after payment is confirmed.');
            if ($receipt = $invoice->receipt) { return $receipt; }
            return InvoiceReceipt::create([
                'invoice_id' => $invoice->id,
                'number' => 'RCT-'.$invoice->paid_at->format('Y').'-'.strtoupper(Str::random(10)),
                'issued_at' => $invoice->paid_at,
                'details' => [
                    'invoice_number' => $invoice->number, 'title' => $invoice->title,
                    'business_name' => config('nylonpay.business_name'),
                    'business_email' => config('nylonpay.business_email'),
                    'business_email_secondary' => config('nylonpay.business_email_secondary'),
                    'business_phone' => config('nylonpay.business_phone'),
                    'business_phone_secondary' => config('nylonpay.business_phone_secondary'),
                    'business_address' => config('nylonpay.business_address'),
                    'client_name' => $invoice->client_name, 'client_business' => $invoice->client_business,
                    'client_email' => $invoice->client_email, 'client_address' => $invoice->client_address,
                    'currency' => $invoice->currency, 'total' => $invoice->total,
                    'payment_reference' => $invoice->manual_payment_reference ?: $invoice->payment_reference,
                    'provider' => $invoice->marked_paid_by ? 'Manual payment' : 'Nylon Pay',
                    'payment_method' => $invoice->manual_payment_method,
                    'confirmation_source' => $invoice->marked_paid_by ? 'admin' : 'provider',
                    'paid_at' => $invoice->paid_at->toIso8601String(),
                    'items' => $invoice->items->map(fn ($item) => $item->only(['description', 'quantity', 'unit_price', 'total']))->all(),
                ],
            ]);
        });
    }

    public function download(Invoice $invoice, bool $inline = false)
    {
        $receipt = $this->issue($invoice);
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isPhpEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');
        $pdf = new Dompdf($options);
        $pdf->loadHtml(view('invoices.receipt-pdf', ['receipt' => $receipt, 'details' => $receipt->details])->render());
        $pdf->setPaper('A4');
        $pdf->render();
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => ($inline ? 'inline' : 'attachment').'; filename="'.$receipt->number.'.pdf"',
            'Cache-Control' => 'private, no-store',
            'Referrer-Policy' => 'no-referrer',
            'X-Robots-Tag' => 'noindex, nofollow',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
