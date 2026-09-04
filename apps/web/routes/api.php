<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin', \App\Http\Middleware\EnsureInvoiceTokenAbility::class])->group(function () {
    Route::apiResource('invoices', \App\Http\Controllers\Billing\AdminInvoiceController::class)->except('destroy')->names('api.invoices');
    Route::post('invoices/{invoice}/issue', [\App\Http\Controllers\Billing\AdminInvoiceController::class, 'issue']);
    Route::post('invoices/{invoice}/void', [\App\Http\Controllers\Billing\AdminInvoiceController::class, 'void']);
});
Route::post('v1/webhooks/nylonpay', \App\Http\Controllers\Billing\NylonPayWebhookController::class);

Route::post('v1/auth/token', [\App\Http\Controllers\Billing\ApiSessionController::class, 'store'])->middleware('throttle:5,1');
Route::delete('v1/auth/token', [\App\Http\Controllers\Billing\ApiSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('v1/admin/invoices/{invoice}/receipt', [\App\Http\Controllers\Billing\AdminInvoiceController::class, 'receipt'])
    ->middleware(['auth:sanctum', 'admin', \App\Http\Middleware\EnsureInvoiceTokenAbility::class, 'throttle:10,1'])->name('api.invoices.receipt');

Route::post('v1/admin/invoices/{invoice}/mark-paid', [\App\Http\Controllers\Billing\AdminInvoiceController::class, 'markPaid'])->middleware(['auth:sanctum', 'admin', \App\Http\Middleware\EnsureInvoiceTokenAbility::class])->name('api.invoices.mark-paid');
