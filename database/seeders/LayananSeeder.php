<?php

namespace Database\Seeders;

use App\Models\BludUnit;
use App\Models\PklPlace;
use App\Models\ProgramKeahlian;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/layanan.json');

        $data = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $this->seedBludUnit($data['blud_unit'] ?? []);
        $this->seedPklPlace($data['pkl_place'] ?? []);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function seedBludUnit(array $items): void
    {
        $names = [];

        foreach ($items as $index => $item) {
            BludUnit::updateOrCreate(
                ['name' => $item['name']],
                [
                    'is_published' => true,
                    'sort_order' => $index + 1,
                ]
            );

            $names[] = $item['name'];
        }

        BludUnit::whereNotIn('name', $names)->delete();
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function seedPklPlace(array $items): void
    {
        $names = [];

        foreach ($items as $index => $item) {
            $program = ProgramKeahlian::where('slug', $item['program'] ?? '')->first();

            PklPlace::updateOrCreate(
                ['name' => $item['name']],
                [
                    'program_keahlian_id' => $program?->id,
                    'is_published' => true,
                    'sort_order' => $index + 1,
                ]
            );

            $names[] = $item['name'];
        }

        PklPlace::whereNotIn('name', $names)->delete();
    }
}
