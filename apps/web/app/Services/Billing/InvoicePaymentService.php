<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvoicePaymentService
{
    public function __construct(private NylonPayGateway $gateway) {}

    public function checkout(Invoice $invoice): string
    {
        abort_unless($this->gateway->configured(), 503, 'Payments are being set up. Please contact the business.');
        // Persist the claim BEFORE the remote call: a timeout must never generate a second invoice.
        $invoice = DB::transaction(function () use ($invoice) {
            $locked = Invoice::lockForUpdate()->findOrFail($invoice->id);
            abort_unless($locked->status === 'issued', 409, 'This invoice is not payable.');
            if ($locked->checkout_url) { return $locked; }
            abort_unless($locked->checkout_state === 'not_started', 409,
                'Checkout is awaiting confirmation. Please contact the business before trying again.');
            $locked->update(['checkout_state' => 'creating']);
            return $locked;
        });
        if ($invoice->checkout_url) { return $invoice->checkout_url; }
        try {
            $checkout = $this->gateway->createCheckout($invoice->load('items'));
            $invoice->update(['checkout_url' => $checkout['url'], 'provider_invoice_id' => $checkout['id'], 'checkout_state' => 'ready']);
            return $checkout['url'];
        } catch (\Throwable $e) {
            $invoice->update(['checkout_state' => 'uncertain']);
            Log::warning('Invoice checkout requires reconciliation.', ['invoice_id' => $invoice->id]);
            throw new HttpException(503, 'We could not confirm checkout. Please contact the business; do not make a second payment.');
        }
    }

    public function reconcile(Invoice $invoice): Invoice
    {
        if ($invoice->status !== 'issued' || $invoice->checkout_state === 'not_started') { return $invoice; }
        $transaction = $this->gateway->transaction($invoice->payment_reference);
        $this->applyTransaction($invoice, $transaction);
        $invoice->update(['last_checked_at' => now()]);
        return $invoice->fresh();
    }

    public function applyTransaction(Invoice $invoice, array $transaction): void
    {
        $amount = (string) ($transaction['amount'] ?? '');
        // Provider amounts may be decimal strings, but only an exact whole UGX amount is accepted.
        abort_unless(preg_match('/^([0-9]+)(?:\.0+)?$/D', $amount, $match)
            && (int) $match[1] === $invoice->total
            && ($transaction['currency'] ?? null) === $invoice->currency
            && ($transaction['reference'] ?? null) === $invoice->payment_reference
            && ($transaction['type'] ?? null) === 'charge', 422, 'Payment does not match this invoice.');
        DB::transaction(function () use ($invoice, $transaction) {
            $invoice = Invoice::lockForUpdate()->findOrFail($invoice->id);
            if ($invoice->status === 'paid' || $invoice->status !== 'issued' || $invoice->checkout_state === 'not_started') { return; }
            $status = $transaction['status'] ?? '';
            if (!in_array($status, ['successful', 'processing', 'pending', 'failed', 'cancelled'])) { return; }
            // Late processing events cannot overwrite a terminal failed/cancelled attempt.
            if (in_array($invoice->payment_status, ['failed', 'cancelled']) && $status !== 'successful') { return; }
            $invoice->payment_status = $status;
            if ($status === 'successful') {
                $invoice->status = 'paid';
                $invoice->paid_at = now();
            }
            $invoice->save();
            if ($invoice->status === 'paid') { app(ReceiptService::class)->issue($invoice); }
        });
    }
}
