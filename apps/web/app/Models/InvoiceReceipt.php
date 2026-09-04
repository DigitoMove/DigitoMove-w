<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InvoiceReceipt extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $casts = ['details' => 'array', 'issued_at' => 'datetime'];
}
