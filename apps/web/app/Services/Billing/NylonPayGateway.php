<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use RuntimeException;
use function NileSquad\NylonPay\createNylonPay;

class NylonPayGateway
{
    public function configured(): bool
    {
        return filled(config('nylonpay.api_key')) && filled(config('nylonpay.api_secret'));
    }

    protected function client()
    {
        if (!$this->configured()) { throw new RuntimeException('Payments are not configured.'); }
        return createNylonPay([
            'apiKey' => config('nylonpay.api_key'), 'apiSecret' => config('nylonpay.api_secret'),
            'baseUrl' => config('nylonpay.base_url'), 'timeoutMs' => 15000, 'maxRetries' => 0,
        ]);
    }

    public function createCheckout(Invoice $invoice): array
    {
        $result = $this->client()->createInvoice([
            'amount' => $invoice->total, 'currency' => $invoice->currency,
            'customerEmail' => $invoice->client_email, 'customerName' => $invoice->client_name,
            'customerPhone' => $invoice->client_phone, 'description' => $invoice->title,
            'merchantReference' => $invoice->payment_reference,
            'reference' => $invoice->payment_reference,
            'redirectUrl' => $invoice->share_url,
            'items' => $invoice->items->map(fn ($item) => [
                'name' => $item->description, 'quantity' => $item->quantity, 'unitPrice' => $item->unit_price,
            ])->all(),
            'metadata' => ['invoice_reference' => $invoice->payment_reference, 'invoice_number' => $invoice->number],
        ]);
        if (!$result->isOk()) { throw new RuntimeException('Checkout could not be confirmed.'); }
        $value = $result->value();
        $url = $value['paymentLink'] ?? $value['url'] ?? null;
        if (!is_string($url) || !filter_var($url, FILTER_VALIDATE_URL) || parse_url($url, PHP_URL_SCHEME) !== 'https'
            || empty($value['id'])) {
            throw new RuntimeException('Invalid checkout response.');
        }
        return ['url' => $url, 'id' => $value['id']];
    }

    public function transaction(string $reference): array
    {
        $result = $this->client()->getTransaction(['reference' => $reference]);
        if (!$result->isOk()) { throw new RuntimeException('Payment status is unavailable.'); }
        return $result->value();
    }
}
