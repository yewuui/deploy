<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        foreach (['ADMIN_NAME', 'ADMIN_EMAIL', 'ADMIN_PASSWORD'] as $key) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);
        }

        parent::tearDown();
    }

    private function seedAdmin(?string $name, ?string $email, ?string $password): void
    {
        foreach (['ADMIN_NAME' => $name, 'ADMIN_EMAIL' => $email, 'ADMIN_PASSWORD' => $password] as $key => $value) {
            if ($value === null) {
                putenv($key);

                continue;
            }

            putenv("{$key}={$value}");
        }

        $this->seed(AdminUserSeeder::class);
    }

    #[Test]
    public function it_creates_no_user_when_admin_credentials_are_missing(): void
    {
        $this->seedAdmin(null, null, null);

        $this->assertSame(0, User::query()->count());
    }

    #[Test]
    public function it_rejects_a_short_admin_password(): void
    {
        $this->seedAdmin('Admin Sekolah', 'admin@smkn1bantul.sch.id', 'pendek123');

        $this->assertSame(0, User::query()->count());
    }

    #[Test]
    public function it_creates_an_admin_account_from_environment(): void
    {
        $this->seedAdmin('Admin Sekolah', 'Admin@Smkn1Bantul.Sch.Id', 'password-yang-kuat-123');

        $user = User::query()->firstOrFail();

        $this->assertSame('admin@smkn1bantul.sch.id', $user->email);
        $this->assertSame('Admin Sekolah', $user->name);
        $this->assertTrue($user->isAdmin());
        $this->assertTrue(Hash::check('password-yang-kuat-123', $user->password));
        $this->assertFalse(Hash::check('password', $user->password));
    }

    #[Test]
    public function it_rotates_the_password_without_duplicating_the_account(): void
    {
        $this->seedAdmin('Admin Sekolah', 'admin@smkn1bantul.sch.id', 'password-yang-kuat-123');
        $this->seedAdmin('Admin Sekolah', 'admin@smkn1bantul.sch.id', 'password-yang-lebih-kuat-456');

        $this->assertSame(1, User::query()->count());
        $this->assertTrue(Hash::check('password-yang-lebih-kuat-456', User::query()->firstOrFail()->password));
    }

    #[Test]
    public function the_default_seeder_never_ships_a_factory_admin(): void
    {
        $this->artisan('db:seed')->assertSuccessful();

        $this->assertFalse(User::query()->where('email', 'test@example.com')->exists());
        $this->assertFalse(User::query()->get()->contains(
            fn (User $user): bool => Hash::check('password', $user->password),
        ));
    }
}
