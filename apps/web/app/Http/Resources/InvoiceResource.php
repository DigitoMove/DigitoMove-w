<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id, 'number' => $this->number, 'title' => $this->title,
            'client_name' => $this->client_name, 'client_email' => $this->client_email,
            'client_business' => $this->client_business, 'client_phone' => $this->client_phone,
            'client_address' => $this->client_address, 'notes' => $this->notes,
            'currency' => $this->currency, 'total' => $this->total, 'status' => $this->status,
            'due_date' => $this->due_date?->format('Y-m-d'), 'is_overdue' => $this->is_overdue,
            'issued_at' => $this->issued_at?->toIso8601String(), 'paid_at' => $this->paid_at?->toIso8601String(),
            'payment_status' => $this->payment_status, 'checkout_state' => $this->checkout_state,
            'share_url' => $this->status !== 'draft' ? $this->share_url : null,
            'receipt_url' => $this->status === 'paid' ? route('api.invoices.receipt', $this->id) : null,
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'description' => $item->description, 'quantity' => $item->quantity,
                'unit_price' => $item->unit_price, 'total' => $item->total,
            ])),
        ];
    }
}
