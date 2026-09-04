<?php

return [
    'api_key' => env('NYLONPAY_API_KEY'),
    'api_secret' => env('NYLONPAY_API_SECRET'),
    'webhook_secret' => env('NYLONPAY_WEBHOOK_SECRET'),
    'base_url' => env('NYLONPAY_BASE_URL', 'https://api.nylonpay.nilesquad.com/api/services'),
    'business_name' => env('INVOICE_BUSINESS_NAME', 'Digito Move'),
    // Canonical billing contact; independent of mail sender/secondary contact settings.
    'business_email' => 'info@digitomove.com',
    'business_phone' => env('INVOICE_BUSINESS_PHONE', '+256701822382'),
    'business_phone_secondary' => env('INVOICE_BUSINESS_PHONE_SECONDARY', '+256777522214'),
    'business_address' => env('INVOICE_BUSINESS_ADDRESS'),
];
