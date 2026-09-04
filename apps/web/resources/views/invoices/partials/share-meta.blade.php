@php
    // Keep messaging cards generic: clients, balances and payment status must not appear in cached previews.
    $shareTitle = 'Invoice from '.config('nylonpay.business_name');
    $shareDescription = 'Open your invoice to review the details, make a payment, or download your receipt after payment.';
    $shareImage = asset('assets/img/social/invoice-preview.png');
@endphp
<meta name="description" content="{{ $shareDescription }}">
<link rel="canonical" href="{{ $invoice->share_url }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('nylonpay.business_name') }}">
<meta property="og:title" content="{{ $shareTitle }}">
<meta property="og:description" content="{{ $shareDescription }}">
<meta property="og:url" content="{{ $invoice->share_url }}">
<meta property="og:image" content="{{ $shareImage }}">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Digito Move — your invoice, ready to view">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $shareTitle }}">
<meta name="twitter:description" content="{{ $shareDescription }}">
<meta name="twitter:image" content="{{ $shareImage }}">
<meta name="twitter:image:alt" content="Digito Move — your invoice, ready to view">
