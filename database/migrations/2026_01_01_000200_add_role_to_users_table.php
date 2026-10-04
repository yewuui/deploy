<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('email');
            });
        }

        if (Schema::hasColumn('users', 'is_admin')) {
            DB::table('users')->where('is_admin', true)->update(['role' => 'admin']);
            DB::table('users')->whereNull('role')->update(['role' => 'user']);

            return;
        }

        DB::table('users')->whereNull('role')->update(['role' => 'user']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
