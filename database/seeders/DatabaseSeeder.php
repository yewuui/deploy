<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SiteSettingSeeder::class,
            ProgramKeahlianSeeder::class,
            SaranaPrasaranaSeeder::class,
            SiteContentSeeder::class,
            LayananSeeder::class,
            ArticleSeeder::class,
            DownloadSeeder::class,
            ProgramSeeder::class,
            EdurouteContentSeeder::class,
            DemoContentSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
