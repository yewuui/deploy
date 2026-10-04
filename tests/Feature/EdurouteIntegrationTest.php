<?php

namespace Tests\Feature;

use App\Models\Career;
use App\Models\Curriculum;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Partner;
use App\Models\PpdbProgram;
use App\Models\Program;
use App\Models\ProgramAchievement;
use App\Models\ProgramKeahlian;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class EdurouteIntegrationTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function program(array $attributes = []): Program
    {
        return Program::create(array_merge([
            'name' => 'Rekayasa Perangkat Lunak',
            'short_name' => 'RPL',
            'slug' => 'rpl',
            'description' => 'Program keahlian perangkat lunak.',
            'is_active' => true,
        ], $attributes));
    }

    public function test_program_cards_open_the_matching_eduroute_dashboard(): void
    {
        $programKeahlian = ProgramKeahlian::factory()->create([
            'slug' => 'rekayasa-perangkat-lunak',
            'title' => 'Rekayasa Perangkat Lunak',
            'is_published' => true,
        ]);

        $this->program(['program_keahlian_id' => $programKeahlian->id]);

        $this->get(route('program-keahlian.index'))
            ->assertOk()
            ->assertSee(route('eduroute.dashboard', 'rpl'), escape: false)
            ->assertSeeInOrder(['Rekayasa Perangkat Lunak', route('eduroute.dashboard', 'rpl')], false);
    }

    public function test_ppdb_program_cards_open_the_matching_eduroute_dashboard(): void
    {
        $programKeahlian = ProgramKeahlian::factory()->create([
            'slug' => 'bisnis-ritel',
            'title' => 'Bisnis Ritel',
            'is_published' => true,
        ]);

        $this->program([
            'slug' => 'br',
            'short_name' => 'BR',
            'name' => 'Bisnis Ritel',
            'program_keahlian_id' => $programKeahlian->id,
        ]);

        PpdbProgram::create([
            'category' => 'Bisnis',
            'title' => 'Bisnis Ritel',
            'description' => 'Mengelola toko, pelayanan pelanggan, dan strategi penjualan.',
            'program_keahlian_id' => $programKeahlian->id,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSeeInOrder(['Bisnis Ritel', route('eduroute.dashboard', 'br')], false);
    }

    public function test_program_cards_keep_the_school_page_without_eduroute_content(): void
    {
        $programKeahlian = ProgramKeahlian::factory()->create(['is_published' => true]);

        $this->get(route('program-keahlian.index'))
            ->assertOk()
            ->assertSee(route('program-keahlian.show', $programKeahlian), escape: false);
    }

    public function test_program_cards_keep_the_school_page_for_an_inactive_eduroute_program(): void
    {
        $programKeahlian = ProgramKeahlian::factory()->create([
            'slug' => 'teknik-komputer-dan-jaringan',
            'title' => 'Teknik Komputer dan Jaringan',
            'is_published' => true,
        ]);

        $this->program([
            'slug' => 'tkj',
            'short_name' => 'TKJ',
            'name' => 'Teknik Komputer dan Jaringan',
            'is_active' => false,
            'program_keahlian_id' => $programKeahlian->id,
        ]);

        $this->get(route('program-keahlian.index'))
            ->assertOk()
            ->assertSee(route('program-keahlian.show', $programKeahlian), escape: false)
            ->assertDontSee(route('eduroute.dashboard', 'tkj'), escape: false);
    }

    public function test_navbar_links_program_keahlian_to_the_eduroute_page(): void
    {
        $programKeahlian = ProgramKeahlian::create([
            'slug' => 'rekayasa-perangkat-lunak',
            'title' => 'Rekayasa Perangkat Lunak',
            'is_published' => true,
        ]);

        $program = $this->program(['program_keahlian_id' => null]);
        $program->update(['program_keahlian_id' => $programKeahlian->id]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('eduroute.dashboard', $program), escape: false);
    }

    public function test_navbar_falls_back_to_school_page_when_eduroute_content_is_missing(): void
    {
        $programKeahlian = ProgramKeahlian::create([
            'slug' => 'desain-komunikasi-visual',
            'title' => 'Desain Komunikasi Visual',
            'is_published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('program-keahlian.show', $programKeahlian), escape: false);
    }

    public function test_navbar_skips_inactive_eduroute_programs(): void
    {
        $programKeahlian = ProgramKeahlian::create([
            'slug' => 'teknik-komputer-dan-jaringan',
            'title' => 'Teknik Komputer dan Jaringan',
            'is_published' => true,
        ]);

        $this->program([
            'slug' => 'tkj',
            'short_name' => 'TKJ',
            'name' => 'Teknik Komputer dan Jaringan',
            'is_active' => false,
            'program_keahlian_id' => $programKeahlian->id,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('program-keahlian.show', $programKeahlian), escape: false);
    }

    public function test_public_eduroute_pages_render_for_an_active_program(): void
    {
        $program = $this->program();

        Curriculum::create([
            'program_id' => $program->id,
            'grade' => 10,
            'title' => 'Pengantar Informatika',
            'topics' => ['Algoritma', 'Flowchart'],
            'order_number' => 1,
        ]);

        Teacher::create(['program_id' => $program->id, 'name' => 'Budi', 'subject' => 'RPL']);
        Career::create(['program_id' => $program->id, 'title' => 'Fullstack Developer']);
        ProgramAchievement::create(['program_id' => $program->id, 'title' => 'Juara 1 LKS']);
        Media::create(['program_id' => $program->id, 'platform' => 'Instagram']);
        Partner::create(['program_id' => $program->id, 'name' => 'PT Contoh']);

        foreach (['dashboard', 'roadmap', 'prospek', 'prestasi', 'media'] as $page) {
            $this->get(route("eduroute.{$page}", $program))
                ->assertOk()
                ->assertSee('Rekayasa Perangkat Lunak', escape: false);
        }
    }

    public function test_roadmap_clamps_an_unsupported_grade_to_ten(): void
    {
        $program = $this->program();

        $this->get(route('eduroute.roadmap', ['program' => $program, 'grade' => 99]))
            ->assertOk()
            ->assertSee('Kelas 10');
    }

    public function test_dashboard_credits_competition_organizers_with_a_link_to_their_site(): void
    {
        $this->seed(SiteContentSeeder::class);

        $program = $this->program();

        $this->get(route('eduroute.dashboard', $program))
            ->assertOk()
            ->assertSee('Support By')
            ->assertSee('images/penyelenggara/jhic.png', escape: false)
            ->assertSee('images/penyelenggara/komdigi.png', escape: false)
            ->assertSee('https://www.komdigi.go.id/', escape: false);

        $this->get(route('eduroute.roadmap', $program))
            ->assertOk()
            ->assertDontSee('images/penyelenggara/jhic.png', escape: false);
    }

    public function test_dashboard_renders_an_organizer_without_a_link_when_its_scheme_is_not_allowed(): void
    {
        Gallery::create([
            'image' => 'images/penyelenggara/ngalup.png',
            'website_url' => 'javascript:alert(1)',
            'alt' => 'Ngalup',
            'group' => 'penyelenggara',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $program = $this->program();

        $response = $this->get(route('eduroute.dashboard', $program))->assertOk();

        // The logo is still shown, but never as an executable link target.
        $response->assertSee('images/penyelenggara/ngalup.png', escape: false)
            ->assertDontSee('javascript:alert(1)', escape: false);
    }

    public function test_inactive_program_returns_404(): void
    {
        $program = $this->program(['is_active' => false]);

        $this->get(route('eduroute.dashboard', $program))->assertNotFound();
    }

    public function test_unknown_program_returns_404(): void
    {
        $this->get('/eduroute/tidak-ada')->assertNotFound();
    }

    public function test_eduroute_admin_routes_require_authentication(): void
    {
        $this->get(route('admin.eduroute.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_reach_eduroute_admin(): void
    {
        $user = User::factory()->nonAdmin()->create();

        $this->actingAs($user)
            ->get(route('admin.eduroute.index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_the_eduroute_program_picker(): void
    {
        $this->program();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.eduroute.index'))
            ->assertOk()
            ->assertSee('Kelola EduRoute')
            ->assertSee('Rekayasa Perangkat Lunak');
    }

    public function test_admin_can_open_eduroute_module_pages(): void
    {
        $program = $this->program();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.eduroute.show', $program))
            ->assertOk()
            ->assertSee('Ringkasan Jurusan');
    }

    public function test_admin_can_create_a_curriculum_stage(): void
    {
        $program = $this->program();

        $this->actingAs(User::factory()->create())
            ->post(route('admin.eduroute.curriculum.store', $program), [
                'grade' => 10,
                'semester' => 1,
                'title' => 'Basis Data',
                'topics' => 'SQL, Relasi',
                'order_number' => 2,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('curriculums', [
            'program_id' => $program->id,
            'grade' => 10,
            'title' => 'Basis Data',
        ]);
    }

    public function test_repeated_teacher_form_submission_only_creates_one_record(): void
    {
        $program = $this->program();
        $payload = [
            'submission_token' => '123e4567-e89b-42d3-a456-426614174000',
            'name' => 'Guru Contoh',
            'subject' => 'Akuntansi',
            'order_number' => 0,
        ];

        $this->actingAs(User::factory()->create())
            ->post(route('admin.eduroute.teachers.store', $program), $payload)
            ->assertRedirect();

        $this->post(route('admin.eduroute.teachers.store', $program), $payload)
            ->assertRedirect(route('admin.eduroute.teachers.index', $program));

        $this->assertSame(1, Teacher::query()->where('program_id', $program->id)->count());
    }

    public function test_legacy_eduroute_urls_redirect_permanently(): void
    {
        $this->get('/roadmap')->assertStatus(301)->assertRedirect('/eduroute/rpl/roadmap');
    }

    public function test_legacy_redirect_keeps_the_query_string(): void
    {
        $this->get('/roadmap?grade=11')
            ->assertStatus(301)
            ->assertRedirect('/eduroute/rpl/roadmap?grade=11');
    }

    public function test_program_keahlian_page_is_still_reachable(): void
    {
        $programKeahlian = ProgramKeahlian::factory()->create(['is_published' => true]);

        $this->get(route('program-keahlian.show', $programKeahlian))->assertOk();
    }
}
