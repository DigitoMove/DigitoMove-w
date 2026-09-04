<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Billing\InvoicePaymentService;
use App\Services\Billing\NylonPayGateway;
use Illuminate\Http\Request;

class PublicInvoiceController extends Controller
{
    private function find(string $token): Invoice
    {
        return Invoice::where('public_token', $token)->where('status', '!=', 'draft')->with('items')->firstOrFail();
    }
    public function show(string $token, NylonPayGateway $gateway)
    {
        return response()->view('invoices.show', ['invoice' => $this->find($token), 'configured' => $gateway->configured()])
            ->header('Cache-Control', 'private, no-store')->header('Referrer-Policy', 'no-referrer')->header('X-Robots-Tag', 'noindex, nofollow');
    }
    public function receipt(string $token, \App\Services\Billing\ReceiptService $receipts)
    {
        return $receipts->download($this->find($token));
    }
    public function confirmPhone(string $token, NylonPayGateway $gateway)
    {
        $invoice = $this->find($token);
        if ($invoice->status !== 'issued') { return redirect()->route('invoices.show', $token); }
        return response()->view('invoices.confirm-phone', ['invoice' => $invoice, 'configured' => $gateway->configured()])
            ->header('Cache-Control', 'private, no-store')->header('Referrer-Policy', 'no-referrer')->header('X-Robots-Tag', 'noindex, nofollow');
    }
    public function checkout(\App\Http\Requests\ConfirmInvoicePhoneRequest $request, string $token, InvoicePaymentService $payments)
    {
        $invoice = $this->find($token);
        try { return redirect()->away($payments->checkout($invoice, $request->validated()['phone']))->header('Referrer-Policy', 'no-referrer'); }
        catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->route('invoices.confirm-phone', $token)->withInput($request->only('phone'))->with('payment_error', $e->getMessage());
        }
    }
    public function refresh(string $token, InvoicePaymentService $payments)
    {
        $invoice = $this->find($token);
        try { $payments->reconcile($invoice); }
        catch (\Throwable $e) { return back()->with('payment_error', 'Payment confirmation is not available yet. Please check again shortly.'); }
        return back()->with('payment_message', 'Payment status checked.');
    }
}
