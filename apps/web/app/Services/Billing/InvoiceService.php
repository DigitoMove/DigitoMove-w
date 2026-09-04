<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    public function save(array $data, int $adminId, ?Invoice $invoice = null): Invoice
    {
        return DB::transaction(function () use ($data, $adminId, $invoice) {
            if ($invoice) {
                $invoice = Invoice::lockForUpdate()->findOrFail($invoice->id);
                abort_unless($invoice->status === 'draft', 409, 'Only drafts can be edited.');
            }
            $items = collect($data['items'])->map(fn ($item) => [
                'description' => $item['description'], 'quantity' => (int) $item['quantity'],
                'unit_price' => (int) $item['unit_price'], 'total' => (int) $item['quantity'] * (int) $item['unit_price'],
            ]);
            $total = $items->sum('total');
            if ($total < 500 || $total > 1000000000) {
                throw ValidationException::withMessages(['items' => 'The invoice total must be between UGX 500 and UGX 1,000,000,000.']);
            }
            unset($data['items']);
            $invoice = $invoice ?? new Invoice([
                'number' => 'INV-'.now()->format('Y').'-'.strtoupper(Str::random(8)),
                'public_token' => Str::random(64), 'created_by' => $adminId,
                'payment_reference' => bin2hex(random_bytes(7)), 'status' => 'draft',
            ]);
            $invoice->fill($data + ['total' => $total])->save();
            $invoice->items()->delete();
            $invoice->items()->createMany($items->all());
            return $invoice->load('items');
        });
    }

    public function issue(Invoice $invoice): Invoice
    {
        return DB::transaction(function () use ($invoice) {
            $invoice = Invoice::lockForUpdate()->findOrFail($invoice->id);
            abort_unless($invoice->status === 'draft', 409, 'Only drafts can be issued.');
            $invoice->update(['status' => 'issued', 'issued_at' => now()]);
            return $invoice;
        });
    }

    public function void(Invoice $invoice): Invoice
    {
        return DB::transaction(function () use ($invoice) {
            $invoice = Invoice::lockForUpdate()->findOrFail($invoice->id);
            abort_unless(in_array($invoice->status, ['draft', 'issued']) && $invoice->checkout_state === 'not_started', 409,
                'An invoice with a started checkout cannot be voided here. Resolve it with Nylon Pay first.');
            $invoice->update(['status' => 'void']);
            return $invoice;
        });
    }
}
