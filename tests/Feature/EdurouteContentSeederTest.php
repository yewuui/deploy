<?php

namespace Tests\Feature;

use App\Models\Career;
use App\Models\Curriculum;
use App\Models\Media;
use App\Models\Partner;
use App\Models\Program;
use App\Models\ProgramAchievement;
use App\Models\ProgramKeahlian;
use App\Models\Teacher;
use Database\Seeders\EdurouteContentSeeder;
use Database\Seeders\ProgramKeahlianSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class EdurouteContentSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return json_decode(
            file_get_contents(database_path('data/eduroute.json')),
            true,
            flags: JSON_THROW_ON_ERROR
        );
    }

    /**
     * @return list<int>
     */
    private function counts(): array
    {
        return [
            Program::count(),
            Curriculum::count(),
            Teacher::count(),
            Career::count(),
            ProgramAchievement::count(),
            Media::count(),
            Partner::count(),
        ];
    }

    public function test_seeder_imports_every_program_from_the_json_snapshot(): void
    {
        $payload = $this->payload();

        $this->seed([ProgramKeahlianSeeder::class, EdurouteContentSeeder::class]);

        $this->assertSame(count($payload['programs']), Program::count());

        foreach ($payload['programs'] as $program) {
            $this->assertDatabaseHas('programs', ['slug' => $program['slug']]);
        }
    }

    public function test_seeder_replaces_child_tables_with_the_json_snapshot(): void
    {
        $payload = $this->payload();

        $this->seed([ProgramKeahlianSeeder::class, EdurouteContentSeeder::class]);

        $this->assertSame(count($payload['curriculums']), Curriculum::count());
        $this->assertSame(count($payload['teachers']), Teacher::count());
        $this->assertSame(count($payload['careers']), Career::count());
        $this->assertSame(count($payload['achievements']), ProgramAchievement::count());
        $this->assertSame(count($payload['media']), Media::count());
        $this->assertSame(count($payload['partners']), Partner::count());
    }

    public function test_seeder_can_run_twice_without_duplicating_rows(): void
    {
        $this->seed([ProgramKeahlianSeeder::class, EdurouteContentSeeder::class]);

        $before = $this->counts();

        $this->seed(EdurouteContentSeeder::class);

        $this->assertSame($before, $this->counts());
    }

    public function test_seeder_links_each_program_to_its_program_page(): void
    {
        $payload = $this->payload();

        $this->seed([ProgramKeahlianSeeder::class, EdurouteContentSeeder::class]);

        foreach ($payload['programs'] as $item) {
            $program = Program::where('slug', $item['slug'])->firstOrFail();
            $programKeahlian = ProgramKeahlian::where('slug', $item['program_keahlian'])->firstOrFail();

            $this->assertSame($programKeahlian->id, $program->program_keahlian_id);
        }
    }

    public function test_seeded_content_reaches_the_public_eduroute_pages(): void
    {
        $payload = $this->payload();

        $this->seed([ProgramKeahlianSeeder::class, EdurouteContentSeeder::class]);

        $program = Program::where('slug', $payload['programs'][0]['slug'])->firstOrFail();

        $this->get(route('eduroute.dashboard', $program))->assertOk();
        $this->get(route('eduroute.roadmap', ['program' => $program, 'grade' => 12]))->assertOk();
        $this->get(route('eduroute.prospek', $program))->assertOk();
        $this->get(route('eduroute.prestasi', $program))->assertOk();
        $this->get(route('eduroute.media', $program))->assertOk();
    }
}
