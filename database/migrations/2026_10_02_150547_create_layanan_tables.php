<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blud_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('pkl_places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('program_keahlian_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['program_keahlian_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkl_places');
        Schema::dropIfExists('blud_units');
    }
};
