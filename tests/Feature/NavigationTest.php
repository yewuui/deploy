<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ProgramKeahlian;
use App\Models\SaranaPrasarana;
use Database\Seeders\SiteSettingSeeder;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_marks_only_the_home_menu_entry_as_active_on_the_home_page(): void
    {
        $this->seedMenuContent();

        $active = $this->activeNavLabels(route('home'));

        $this->assertContains('Beranda', $active);
        $this->assertNotContains('Informasi', $active);
        $this->assertFalse($this->hasActiveLabelStartingWith($active, 'Program'));
    }

    public function test_marks_the_article_entry_while_reading_an_article(): void
    {
        $this->seedMenuContent();

        $active = $this->activeNavLabels(route('berita.show', Article::firstOrFail()));

        $this->assertContains('Berita & Informasi', $active);
        $this->assertContains('Informasi', $active);
    }

    public function test_marks_the_facility_entry_while_viewing_a_facility_detail_page(): void
    {
        $this->seedMenuContent();

        $active = $this->activeNavLabels(route('sarana-prasarana.show', SaranaPrasarana::firstOrFail()));

        $this->assertContains('Sarana Prasarana', $active);
        $this->assertContains('Profil Sekolah', $active);
    }

    public function test_marks_only_the_visited_program_while_viewing_a_program_detail_page(): void
    {
        $this->seedMenuContent();

        $program = ProgramKeahlian::where('slug', 'akuntansi')->firstOrFail();

        $active = $this->activeNavLabels(route('program-keahlian.show', $program));

        $this->assertContains('Akuntansi', $active);
        $this->assertTrue($this->hasActiveLabelStartingWith($active, 'Program'));
        $this->assertNotContains('Rekayasa Perangkat Lunak', $active);
    }

    public function test_does_not_mark_the_program_group_active_on_an_unrelated_page(): void
    {
        $this->seedMenuContent();

        $active = $this->activeNavLabels(route('download'));

        $this->assertContains('Download', $active);
        $this->assertContains('Informasi', $active);
        $this->assertFalse($this->hasActiveLabelStartingWith($active, 'Program'));
    }

    public function test_marks_the_program_group_on_the_program_listing_page(): void
    {
        $this->seedMenuContent();

        $active = $this->activeNavLabels(route('program-keahlian.index'));

        $this->assertTrue($this->hasActiveLabelStartingWith($active, 'Program'));
        $this->assertNotContains('Akuntansi', $active);
    }

    public function test_lists_the_services_group_while_reading_blud(): void
    {
        $this->seedMenuContent();

        $active = $this->activeNavLabels(route('blud'));

        $this->assertContains('BLUD', $active);
        $this->assertContains('Layanan', $active);
        $this->assertNotContains('PKL', $active);
    }

    public function test_lists_the_services_group_while_reading_pkl(): void
    {
        $this->seedMenuContent();

        $active = $this->activeNavLabels(route('pkl'));

        $this->assertContains('PKL', $active);
        $this->assertContains('Layanan', $active);
        $this->assertNotContains('BLUD', $active);
    }

    public function test_lists_ppdb_under_the_information_group(): void
    {
        $this->seedMenuContent();

        $active = $this->activeNavLabels(route('ppdb'));

        $this->assertContains('PPDB', $active);
        $this->assertContains('Informasi', $active);
        $this->assertNotContains('Layanan', $active);
    }

    private function seedMenuContent(): void
    {
        $this->seed(SiteSettingSeeder::class);

        foreach ([
            ['rekayasa-perangkat-lunak', 'Rekayasa Perangkat Lunak', 'TIK', 1],
            ['akuntansi', 'Akuntansi', 'Bisnis', 2],
        ] as [$slug, $title, $category, $order]) {
            ProgramKeahlian::create([
                'slug' => $slug,
                'title' => $title,
                'category' => $category,
                'summary' => 'Ringkasan program '.$title.'.',
                'sections' => [],
                'is_published' => true,
                'sort_order' => $order,
            ]);
        }

        Article::create([
            'title' => 'Sekolah menandatangani kerja sama industri',
            'slug' => 'kerja-sama-industri',
            'excerpt' => 'Ringkasan kerja sama.',
            'body' => 'Isi berita.',
            'published_at' => now(),
            'is_published' => true,
        ]);

        SaranaPrasarana::create([
            'slug' => 'perpustakaan',
            'title' => 'Perpustakaan',
            'description' => 'Tempat membaca yang nyaman.',
            'is_published' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * @param  list<string>  $labels
     */
    private function hasActiveLabelStartingWith(array $labels, string $prefix): bool
    {
        foreach ($labels as $label) {
            if (str_starts_with($label, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Label entri navigasi yang sedang ditandai aktif pada halaman tersebut.
     *
     * Navbar dirender dua kali (desktop dan mobile), jadi hasilnya unik.
     *
     * @return list<string>
     */
    private function activeNavLabels(string $url): array
    {
        $response = $this->get($url)->assertOk();

        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8" ?>'.$response->getContent());
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query(
            '//nav[@aria-label="Navigasi utama"]//a'
            .' | //nav[@aria-label="Navigasi utama"]//button'
            .' | //nav[@aria-label="Navigasi utama"]//summary'
        );

        $labels = [];
        foreach ($nodes as $node) {
            if (! str_contains($node->getAttribute('class'), 'bg-blue-50')) {
                continue;
            }

            $text = trim(preg_replace('/\s+/', ' ', $node->textContent) ?? '');
            if ($text !== '') {
                $labels[] = $text;
            }
        }

        return array_values(array_unique($labels));
    }
}
