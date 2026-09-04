<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $casts = ['quantity' => 'integer', 'unit_price' => 'integer', 'total' => 'integer'];
}
