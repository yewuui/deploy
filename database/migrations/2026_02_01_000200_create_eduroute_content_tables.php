<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->integer('grade')->default(10);
            $table->string('type')->nullable();
            $table->integer('semester')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('topics');
            $table->integer('week_start')->nullable();
            $table->integer('week_end')->nullable();
            $table->string('color_theme')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->integer('order_number')->default(0);
            $table->timestamps();

            $table->index(['program_id', 'grade', 'order_number']);
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->string('name');
            $table->string('subject');
            $table->string('role_title')->nullable();
            $table->text('description')->nullable();
            $table->text('expertise')->nullable();
            $table->string('education')->nullable();
            $table->string('campus')->nullable();
            $table->string('photo')->nullable();
            $table->integer('order_number')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->text('details')->nullable();
            $table->json('tasks')->nullable();
            $table->json('tools')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order_number')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('program_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->string('title');
            $table->string('name')->nullable();
            $table->string('date_label')->nullable();
            $table->string('category')->nullable();
            $table->string('level')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('order_number')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->string('platform');
            $table->string('handle')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->integer('order_number')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->string('name');
            $table->string('website_url')->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_bg_color', 7)->nullable();
            $table->integer('order_number')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('media');
        Schema::dropIfExists('program_achievements');
        Schema::dropIfExists('careers');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('curriculums');
    }
};
