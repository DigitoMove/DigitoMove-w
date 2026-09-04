<?php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ProvisionAdmin extends Command
{
    protected $signature = 'admin:provision';
    protected $description = 'Create or update the administrator from private server configuration';

    public function handle()
    {
        $email = config('admin.email');
        $password = config('admin.password');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !is_string($password) || strlen($password) < 12) {
            $this->error('Set ADMIN_EMAIL and ADMIN_PASSWORD (at least 12 characters) in the private server environment.');
            return 1;
        }
        User::updateOrCreate(['email' => $email], [
            'name' => 'Digito Move Admin', 'role' => 'admin',
            'phone' => config('nylonpay.business_phone'), 'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);
        $this->info('Administrator configured: '.$email);
        return 0;
    }
}
