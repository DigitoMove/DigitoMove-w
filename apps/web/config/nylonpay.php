<?php

return [
    'api_key' => env('NYLONPAY_API_KEY'),
    'api_secret' => env('NYLONPAY_API_SECRET'),
    'webhook_secret' => env('NYLONPAY_WEBHOOK_SECRET'),
    'base_url' => env('NYLONPAY_BASE_URL', 'https://api.nylonpay.nilesquad.com/api/services'),
    'business_name' => env('INVOICE_BUSINESS_NAME', 'Digito Move'),
    'business_email' => env('INVOICE_BUSINESS_EMAIL'),
    'business_address' => env('INVOICE_BUSINESS_ADDRESS'),
];
