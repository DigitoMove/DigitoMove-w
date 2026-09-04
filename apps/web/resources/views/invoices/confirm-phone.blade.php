<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><meta name="referrer" content="no-referrer"><title>Confirm payment number · {{ config('nylonpay.business_name') }}</title><link rel="stylesheet" href="{{ \App\Support\LocalAsset::url('assets/css/invoices.css') }}"></head>
<body class="invoice-page"><header class="invoice-brand"><a href="{{ route('home') }}">{{ config('nylonpay.business_name') }}</a><span>Secure checkout</span></header>
<main class="phone-checkout"><a class="phone-back" href="{{ $invoice->share_url }}">← Back to invoice</a><section class="invoice-payment">
<span class="invoice-kicker">Mobile money · Step 1 of 2</span><h2>Confirm your number</h2><p>Enter the mobile money number you want to pay with. You’ll continue to Nylon Pay to authorize the payment.</p>
<div class="receipt-meta"><span>Invoice</span><strong>{{ $invoice->number }}</strong><span>Amount to pay</span><strong>UGX {{ number_format($invoice->total) }}</strong></div>
@if($errors->any())<div class="invoice-alert" role="alert">{{ $errors->first() }}</div>@endif
@if(session('payment_error'))<div class="invoice-alert" role="alert">{{ session('payment_error') }}</div>@endif
@if(in_array($invoice->checkout_state, ['creating','uncertain']))<div class="invoice-alert">Checkout is awaiting confirmation. Please contact the business before starting another payment.</div>
@elseif(!$configured)<div class="invoice-alert">Online payment is being set up. Please contact the business.</div>
@else<form method="POST" action="{{ route('invoices.pay', $invoice->public_token) }}">@csrf
<label for="phone" class="phone-label">Mobile money number</label><input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" class="phone-input" placeholder="e.g. 0771234567" maxlength="30" value="{{ old('phone', $invoice->payer_phone ?? $invoice->client_phone) }}" aria-describedby="phone-help" required @readonly($invoice->checkout_url)>
<p id="phone-help">MTN MoMo or Airtel Money. Use 07… or +2567… format.</p>
@if($invoice->checkout_url)<p>This checkout already uses the confirmed number above. Contact the business if it needs changing.</p>@endif
<label class="phone-confirm"><input type="checkbox" name="confirm_phone" value="1" required><span>I have checked this number and want to use it for this payment.</span></label>
<button class="invoice-button">Confirm number & continue →</button><p class="payment-help">Step 2: authorize your payment on Nylon Pay. We never ask for your mobile money PIN here.</p>
</form>@endif
</section></main></body></html>
