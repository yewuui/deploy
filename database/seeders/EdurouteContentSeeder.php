<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Curriculum;
use App\Models\Media;
use App\Models\Partner;
use App\Models\Program;
use App\Models\ProgramAchievement;
use App\Models\ProgramKeahlian;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Memuat isi EduRoute (roadmap, guru, karier, prestasi, media, mitra) dari
 * database/data/eduroute.json.
 *
 * Berisi JSON adalah hasil revisi dari aplikasi EduRoute dan diperlakukan
 * sebagai sumber kebenaran: isi tabel anak diganti seluruhnya setiap kali
 * seeder berjalan, sehingga hasil seeding selalu sama dengan JSON.
 */
class EdurouteContentSeeder extends Seeder
{
    /**
     * Program hasil seeding, dikunci berdasarkan slug.
     *
     * @var array<string, Program>
     */
    private array $programs = [];

    public function run(): void
    {
        $data = $this->content();

        foreach ($data['programs'] as $program) {
            $this->programs[$program['slug']] = $this->seedProgram($program);
        }

        $this->seedChildren(Curriculum::class, $data['curriculums']);
        $this->seedChildren(Teacher::class, $data['teachers']);
        $this->seedChildren(Career::class, $data['careers']);
        $this->seedChildren(ProgramAchievement::class, $data['achievements']);
        $this->seedChildren(Media::class, $data['media']);
        $this->seedChildren(Partner::class, $data['partners']);
    }

    /**
     * @return array<string, mixed>
     */
    private function content(): array
    {
        $path = database_path('data/eduroute.json');

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * Program di-upsert berdasarkan slug. Kolom bernilai null pada JSON
     * dilewati, bukan ditulis, supaya deskripsi/hero yang sudah diisi di
     * seeder lain tidak tertimpa oleh kekosongan.
     *
     * @param  array<string, mixed>  $item
     */
    private function seedProgram(array $item): Program
    {
        $attributes = collect($item)
            ->except(['program_keahlian'])
            ->reject(fn (mixed $value): bool => $value === null)
            ->all();

        $attributes['program_keahlian_id'] = ProgramKeahlian::where('slug', $item['program_keahlian'])->value('id');

        return Program::updateOrCreate(['slug' => $item['slug']], $attributes);
    }

    /**
     * Mengganti seluruh isi tabel anak dengan isi JSON. Baris JSON dikunci
     * program lewat slug, bukan id, supaya tahan terhadap perubahan id.
     *
     * @param  class-string<Model>  $model
     * @param  list<array<string, mixed>>  $rows
     */
    private function seedChildren(string $model, array $rows): void
    {
        $model::query()->delete();

        foreach ($rows as $row) {
            $program = $this->programs[$row['program']] ?? null;

            if (! $program) {
                continue;
            }

            unset($row['program']);

            $model::create(array_merge($row, ['program_id' => $program->id]));
        }
    }
}
