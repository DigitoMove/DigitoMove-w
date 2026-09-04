<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['public_token', 'checkout_url', 'provider_invoice_id'];
    protected $casts = ['total' => 'integer', 'due_date' => 'date', 'issued_at' => 'datetime', 'paid_at' => 'datetime', 'last_checked_at' => 'datetime'];

    public function receipt() { return $this->hasOne(InvoiceReceipt::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function getShareUrlAttribute() { return route('invoices.show', $this->public_token); }
    public function getIsOverdueAttribute() { return $this->status === 'issued' && $this->due_date && $this->due_date->lt(today()); }
}
