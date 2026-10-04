<?php

namespace Database\Factories;

use App\Models\ProgramKeahlian;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProgramKeahlian>
 */
class ProgramKeahlianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->randomElement([
            'Rekayasa Perangkat Lunak',
            'Akuntansi dan Lembaga Keuangan',
            'Bisnis Digital',
            'Bisnis Ritel',
            'Desain Komunikasi Visual',
            'Layanan Perbankan Syariah',
            'Manajemen Perkantoran dan Layanan Bisnis',
            'Teknik Komputer dan Jaringan',
        ]).' '.fake()->unique()->numberBetween(1, 9999);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(['Bisnis', 'Manajemen', 'Seni & Kreatif', 'TIK']),
            'image' => null,
            'summary' => fake()->sentence(10),
            'sections' => [],
            'is_published' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
