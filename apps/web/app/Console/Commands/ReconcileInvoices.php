<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\Billing\InvoicePaymentService;
use Illuminate\Console\Command;

class ReconcileInvoices extends Command
{
    protected $signature = 'invoices:reconcile';
    protected $description = 'Recheck unpaid invoices with a started Nylon Pay checkout';

    public function handle(InvoicePaymentService $payments)
    {
        $failed = 0;
        Invoice::where('status', 'issued')->where('checkout_state', '!=', 'not_started')->chunkById(100, function ($invoices) use ($payments, &$failed) {
            foreach ($invoices as $invoice) {
                try { $payments->reconcile($invoice); }
                catch (\Throwable $e) { $failed++; $this->warn("Status unavailable for {$invoice->number}; retained for reconciliation."); }
            }
        });
        return $failed ? 1 : 0;
    }
}
