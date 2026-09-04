<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('level')->default('beginner')->index();
            $table->string('summary', 500);
            $table->longText('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->decimal('tuition_fee', 12, 2)->default(0);
            $table->decimal('application_fee', 12, 2)->default(0);
            $table->string('duration')->nullable();
            $table->string('delivery_mode')->default('hybrid');
            $table->string('status')->default('draft')->index();
            $table->boolean('applications_open')->default(true);
            $table->timestamps();
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('video_url')->nullable();
            $table->string('resource_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->timestamps();
            $table->unique(['course_id', 'slug']);
        });

        Schema::create('course_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('education_level')->nullable();
            $table->text('motivation')->nullable();
            $table->string('status')->default('submitted')->index();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_application_id')->constrained()->cascadeOnDelete();
            $table->string('purpose')->default('application_fee');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('UGX');
            $table->string('provider')->default('manual');
            $table->string('reference')->unique();
            $table->string('status')->default('pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('course_applications');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('courses');
    }
};
