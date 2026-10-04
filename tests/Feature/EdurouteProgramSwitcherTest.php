<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\ProgramKeahlian;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class EdurouteProgramSwitcherTest extends TestCase
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

    private function makeSecondProgram(): Program
    {
        $programKeahlian = ProgramKeahlian::factory()->create([
            'slug' => 'teknik-komputer-dan-jaringan',
            'title' => 'Teknik Komputer dan Jaringan',
            'is_published' => true,
        ]);

        return $this->program([
            'slug' => 'tkj',
            'short_name' => 'TKJ',
            'name' => 'Teknik Komputer dan Jaringan',
            'program_keahlian_id' => $programKeahlian->id,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->program();
    }

    public function test_eduroute_header_renders_the_program_switcher(): void
    {
        $this->makeSecondProgram();

        $this->get(route('eduroute.dashboard', 'rpl'))
            ->assertOk()
            ->assertSee('Pilih Jurusan')
            ->assertSee('data-program-switcher')
            ->assertSee('RPL')
            ->assertSee('TKJ')
            ->assertSee('Rekayasa Perangkat Lunak')
            ->assertSee('Teknik Komputer dan Jaringan');
    }

    public function test_eduroute_header_marks_the_active_program_as_selected(): void
    {
        $this->makeSecondProgram();

        $this->get(route('eduroute.dashboard', 'tkj'))
            ->assertOk()
            ->assertSee('data-active="true"', escape: false)
            ->assertSee('aria-current="page"', escape: false)
            ->assertSee('data-slug="tkj"', escape: false);
    }

    public function test_program_switcher_keeps_the_same_eduroute_page(): void
    {
        $this->makeSecondProgram();

        $this->get(route('eduroute.roadmap', ['program' => 'rpl', 'grade' => 11]))
            ->assertOk()
            ->assertSee(route('eduroute.roadmap', ['program' => 'tkj', 'grade' => 11]), escape: false)
            ->assertDontSee('href="'.route('eduroute.dashboard', 'tkj').'"', escape: false);
    }

    public function test_program_switcher_lists_only_active_programs(): void
    {
        $this->program([
            'slug' => 'tkj',
            'short_name' => 'TKJ',
            'name' => 'Teknik Komputer dan Jaringan',
            'is_active' => false,
        ]);

        $this->get(route('eduroute.dashboard', 'rpl'))
            ->assertOk()
            ->assertSee('RPL')
            ->assertDontSee('TKJ', escape: false);
    }

    public function test_program_switcher_is_present_on_every_eduroute_page(): void
    {
        $this->makeSecondProgram();

        foreach (['dashboard', 'roadmap', 'prospek', 'prestasi', 'media'] as $page) {
            $this->get(route("eduroute.{$page}", 'rpl'))
                ->assertOk()
                ->assertSee('Pilih Jurusan');
        }
    }

    public function test_eduroute_header_shows_the_active_program_identity(): void
    {
        $this->makeSecondProgram();

        $this->get(route('eduroute.dashboard', 'tkj'))
            ->assertOk()
            ->assertSee('Teknik Komputer dan Jaringan')
            ->assertSee('id="program-identity-name"', escape: false);
    }

    public function test_eduroute_header_keeps_the_night_light_toggle_to_the_right_of_the_switcher(): void
    {
        $this->makeSecondProgram();

        $response = $this->get(route('eduroute.dashboard', 'rpl'))->assertOk();

        $switcherPos = strpos($response->getContent(), 'data-program-switcher-trigger');
        $themePos = strpos($response->getContent(), 'id="theme-toggle"');

        $this->assertNotFalse($switcherPos, 'pemilih jurusan tidak ditemukan');
        $this->assertNotFalse($themePos, 'tombol tema tidak ditemukan');
        $this->assertLessThan($themePos, $switcherPos, 'pemilih jurusan harus di kiri tombol Night/Light');
    }

    public function test_program_switcher_uses_school_program_keahlian_order_instead_of_program_name(): void
    {
        $manajemen = ProgramKeahlian::factory()->create([
            'slug' => 'manajemen-perkantoran',
            'title' => 'Manajemen Perkantoran',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $rpl = ProgramKeahlian::factory()->create([
            'slug' => 'rekayasa-perangkat-lunak',
            'title' => 'Rekayasa Perangkat Lunak',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $tkj = ProgramKeahlian::factory()->create([
            'slug' => 'teknik-komputer-dan-jaringan',
            'title' => 'Teknik Komputer dan Jaringan',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->program([
            'slug' => 'manajemen-perkantoran',
            'short_name' => 'MP',
            'name' => 'Zeta Manajemen',
            'program_keahlian_id' => $manajemen->id,
        ]);

        $this->program([
            'slug' => 'alpha-rekayasa',
            'short_name' => 'RPL',
            'name' => 'Alpha Rekayasa',
            'program_keahlian_id' => $rpl->id,
        ]);

        $this->program([
            'slug' => 'beta-teknik',
            'short_name' => 'TKJ',
            'name' => 'Beta Teknik',
            'program_keahlian_id' => $tkj->id,
        ]);

        $response = $this->get(route('eduroute.dashboard', 'alpha-rekayasa'))->assertOk();

        $response->assertSeeInOrder([
            'Manajemen Perkantoran',
            'Rekayasa Perangkat Lunak',
            'Teknik Komputer dan Jaringan',
        ]);
    }

    public function test_eduroute_header_does_not_show_the_back_to_school_button_as_the_program_switcher(): void
    {
        $this->makeSecondProgram();

        $this->get(route('eduroute.dashboard', 'rpl'))
            ->assertOk()
            ->assertSee('Kembali ke Website Sekolah')
            ->assertSee('Pilih Jurusan');
    }
}
