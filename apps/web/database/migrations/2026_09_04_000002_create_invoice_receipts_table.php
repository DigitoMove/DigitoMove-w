<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('invoice_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->unique()->constrained();
            $table->string('number')->unique();
            $table->json('details');
            $table->timestamp('issued_at');
        });
    }
    public function down() { Schema::dropIfExists('invoice_receipts'); }
};
