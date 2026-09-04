<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><meta name="referrer" content="no-referrer"><title>{{ $invoice->number }} · {{ config('nylonpay.business_name') }}</title>
@include('invoices.partials.share-meta')
<link rel="stylesheet" href="{{ asset('assets/css/invoices.css') }}"></head>
<body class="invoice-page"><header class="invoice-brand"><a href="{{ route('home') }}">{{ config('nylonpay.business_name') }}</a><span>Client invoice</span></header>
<main class="invoice-layout">@include('invoices.details')<aside class="invoice-payment">
@if(session('payment_error'))<div class="invoice-alert" role="alert">{{ session('payment_error') }}</div>@endif
@if(session('payment_message'))<p role="status">{{ session('payment_message') }}</p>@endif
@if($invoice->status === 'paid')<div class="payment-check" aria-hidden="true">✓</div><span class="invoice-kicker">Payment complete</span><h2>All settled.</h2><p>Thank you, {{ $invoice->client_name }}. Your payment has been confirmed and your receipt is ready.</p><strong class="payment-amount">UGX {{ number_format($invoice->total) }}</strong><div class="receipt-meta"><span>Paid on</span><strong>{{ $invoice->paid_at->format('d M Y') }}</strong><span>Balance remaining</span><strong>UGX 0</strong></div><a class="invoice-button receipt-download" href="{{ route('invoices.receipt', $invoice->public_token) }}">↓ Download receipt <span>PDF</span></a><p class="payment-help">A copy for your records, available here whenever you need it.</p>
@elseif($invoice->status === 'void')<h2>Invoice voided</h2><p>This invoice is no longer payable. Contact {{ config('nylonpay.business_name') }} if you need a replacement.</p>
@else<span class="invoice-kicker">Amount due</span><strong class="payment-amount">UGX {{ number_format($invoice->total) }}</strong><p>{{ $invoice->due_date ? 'Due '.$invoice->due_date->format('d M Y') : 'Due on receipt' }}</p>
<div class="payment-method"><strong>Mobile money</strong><p>Continue to Nylon Pay to choose an available payment method and confirm your payment.</p></div>
@if($configured)<a class="invoice-button payment-link" href="{{ route('invoices.confirm-phone', $invoice->public_token) }}">Pay with Nylon Pay →</a>@if(in_array($invoice->checkout_state, ['creating','uncertain']))<p>Checkout is awaiting confirmation. Please contact the business.</p>@endif
@else<div class="invoice-alert">Online payment is being set up. Please contact the business to arrange payment.</div>@endif
@if($invoice->checkout_state !== 'not_started')<form method="POST" action="{{ route('invoices.refresh', $invoice->public_token) }}">@csrf<button class="invoice-button secondary">Check payment status</button></form><p class="payment-help">Already paid? Confirmation may take a moment. Check the status before attempting another payment.</p>@endif
@endif
<button class="invoice-button secondary print-button" type="button" onclick="window.print()">Print / save invoice</button>
<p class="payment-help">Questions about this invoice?@if(config('nylonpay.business_email')) <a href="mailto:{{ config('nylonpay.business_email') }}">Contact the business</a>.@else Contact {{ config('nylonpay.business_name') }}.@endif</p>
</aside></main><footer class="invoice-footer">{{ config('nylonpay.business_name') }} · Payments processed by Nylon Pay</footer></body></html>
