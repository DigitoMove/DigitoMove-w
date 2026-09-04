<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureInvoiceTokenAbility
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->tokenCan('invoices:manage'), 403);
        return $next($request);
    }
}
