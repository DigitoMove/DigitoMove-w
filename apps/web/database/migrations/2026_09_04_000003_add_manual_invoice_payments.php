<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('marked_paid_by')->nullable()->constrained('users');
            $table->string('manual_payment_method', 40)->nullable();
            $table->string('manual_payment_reference', 180)->nullable();
            $table->text('manual_payment_note')->nullable();
        });
    }
    public function down() {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['marked_paid_by']);
            $table->dropColumn(['marked_paid_by', 'manual_payment_method', 'manual_payment_reference', 'manual_payment_note']);
        });
    }
};
