<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Membuat akun admin sekolah dari kredensial environment.
 *
 * Kredensial diambil dari ADMIN_NAME, ADMIN_EMAIL, dan ADMIN_PASSWORD supaya
 * tidak ada akun admin dengan password default yang ikut ter-deploy.
 */
class AdminUserSeeder extends Seeder
{
    private const MINIMUM_PASSWORD_LENGTH = 12;

    public function run(): void
    {
        $email = Str::lower(trim((string) env('ADMIN_EMAIL', '')));
        $password = (string) env('ADMIN_PASSWORD', '');

        if ($email === '' || $password === '') {
            $this->command?->warn('ADMIN_EMAIL / ADMIN_PASSWORD belum diisi, akun admin tidak dibuat.');

            return;
        }

        if (Str::length($password) < self::MINIMUM_PASSWORD_LENGTH) {
            $this->command?->error('ADMIN_PASSWORD minimal '.self::MINIMUM_PASSWORD_LENGTH.' karakter, akun admin tidak dibuat.');

            return;
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => (string) (env('ADMIN_NAME') ?: 'Admin Sekolah'),
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
            ],
        );

        $this->command?->info("Akun admin {$email} siap digunakan.");
    }
}
