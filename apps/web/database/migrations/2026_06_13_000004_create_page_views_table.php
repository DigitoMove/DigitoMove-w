<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained()->nullOnDelete();
            $table->string('path');
            $table->string('referrer')->nullable();
            $table->string('visitor_hash', 64)->nullable()->index();
            $table->timestamp('viewed_at')->useCurrent()->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_views');
    }
};
