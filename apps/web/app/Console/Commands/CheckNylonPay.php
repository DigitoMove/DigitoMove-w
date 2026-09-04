<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function NileSquad\NylonPay\createNylonPay;
use function NileSquad\NylonPay\parseError;

class CheckNylonPay extends Command
{
    protected $signature = 'nylonpay:check';
    protected $description = 'Check payment credentials with a read-only lookup (does not create a payment)';

    public function handle()
    {
        try {
            $client = createNylonPay([
                'apiKey' => config('nylonpay.api_key'), 'apiSecret' => config('nylonpay.api_secret'),
                'baseUrl' => config('nylonpay.base_url'), 'timeoutMs' => 15000, 'maxRetries' => 0,
            ]);
            $result = $client->getTransaction(['reference' => bin2hex(random_bytes(7))]);
            if ($result->isOk() || parseError($result->error())->category === 'not_found') {
                $this->info('Nylon Pay accepted the read-only lookup. No payment was created.');
                return 0;
            }
            $this->error('Connection check failed: '.parseError($result->error())->category);
        } catch (\Throwable $e) { $this->error('Check the payment configuration and connection.'); }
        return 1;
    }
}
