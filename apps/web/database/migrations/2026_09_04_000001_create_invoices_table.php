<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('public_token', 64)->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_business')->nullable();
            $table->string('client_phone', 40)->nullable();
            $table->text('client_address')->nullable();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->string('currency', 3)->default('UGX');
            $table->unsignedBigInteger('total');
            $table->date('due_date')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference', 64)->unique();
            $table->string('provider_invoice_id')->nullable();
            $table->text('checkout_url')->nullable();
            $table->string('checkout_state', 20)->default('not_started');
            $table->string('payment_status', 30)->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_price');
            $table->unsignedBigInteger('total');
        });
        Schema::create('invoice_webhook_deliveries', function (Blueprint $table) {
            $table->string('delivery_id')->primary();
            $table->foreignId('invoice_id')->constrained();
            $table->timestamp('received_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_webhook_deliveries');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
