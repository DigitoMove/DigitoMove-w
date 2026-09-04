<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('website')->index();
            $table->string('client')->nullable();
            $table->string('summary', 500);
            $table->longText('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('screenshots')->nullable();
            $table->string('website_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('demo_username')->nullable();
            $table->string('demo_password')->nullable();
            $table->json('technologies')->nullable();
            $table->string('status')->default('draft')->index();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
