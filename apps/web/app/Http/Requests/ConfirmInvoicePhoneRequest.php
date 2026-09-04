<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmInvoicePhoneRequest extends FormRequest
{
    public function authorize() { return true; }
    protected function prepareForValidation()
    {
        $phone = $this->input('phone');
        if (!is_string($phone)) { return; }
        $phone = preg_replace('/[\s()\-]/', '', $phone);
        if (preg_match('/^07[0-9]{8}$/D', $phone)) { $phone = '+256'.substr($phone, 1); }
        elseif (preg_match('/^2567[0-9]{8}$/D', $phone)) { $phone = '+'.$phone; }
        $this->merge(['phone' => $phone]);
    }
    public function rules()
    {
        return ['phone' => ['required', 'string', 'regex:/^\+2567[0-9]{8}$/D'], 'confirm_phone' => ['required', 'accepted']];
    }
    public function messages()
    {
        return ['phone.regex' => 'Enter a valid Ugandan mobile number, such as 0771234567 or +256771234567.',
            'confirm_phone.accepted' => 'Please confirm that this is the number you want to pay with.',
            'confirm_phone.required' => 'Please confirm that this is the number you want to pay with.'];
    }
}
