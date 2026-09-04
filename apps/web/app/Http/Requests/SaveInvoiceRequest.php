<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveInvoiceRequest extends FormRequest
{
    public function authorize() { return $this->user()?->role === 'admin'; }

    public function rules()
    {
        return [
            'client_name' => 'required|string|max:180',
            'client_email' => 'required|email|max:180',
            'client_business' => 'nullable|string|max:180',
            'client_phone' => 'nullable|string|max:40',
            'client_address' => 'nullable|string|max:1000',
            'title' => 'required|string|max:180',
            'notes' => 'nullable|string|max:5000',
            'currency' => 'required|in:UGX',
            'due_date' => 'nullable|date_format:Y-m-d|after_or_equal:today',
            'items' => 'required|array|min:1|max:50',
            'items.*.description' => 'required|string|max:180',
            'items.*.quantity' => 'required|integer|min:1|max:10000',
            'items.*.unit_price' => 'required|integer|min:1|max:1000000000',
        ];
    }
}
