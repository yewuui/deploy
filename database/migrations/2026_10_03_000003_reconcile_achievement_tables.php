<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('achievements')
            && Schema::hasColumn('achievements', 'program_id')
            && ! Schema::hasTable('program_achievements')
        ) {
            Schema::rename('achievements', 'program_achievements');
        }

        if (Schema::hasTable('achievements')) {
            return;
        }

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('student_name')->nullable();
            $table->string('class_name')->nullable();
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('level')->nullable();
            $table->date('achieved_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (
            Schema::hasTable('program_achievements')
            && Schema::hasColumn('program_achievements', 'program_id')
        ) {
            Schema::dropIfExists('achievements');
            Schema::rename('program_achievements', 'achievements');
        }
    }
};