<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\BludUnit;
use App\Models\Ekstrakurikuler;
use App\Models\Gallery;
use App\Models\ProgramKeahlian;
use App\Models\SaranaPrasarana;
use App\Models\SiteSetting;
use App\Models\TeachingFactory;
use Database\Seeders\ArticleSeeder;
use Database\Seeders\DownloadSeeder;
use Database\Seeders\LayananSeeder;
use Database\Seeders\ProgramKeahlianSeeder;
use Database\Seeders\SaranaPrasaranaSeeder;
use Database\Seeders\SiteContentSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function seedSite(): void
    {
        $this->seed([
            SiteSettingSeeder::class,
            ProgramKeahlianSeeder::class,
            SaranaPrasaranaSeeder::class,
            SiteContentSeeder::class,
            LayananSeeder::class,
            ArticleSeeder::class,
            DownloadSeeder::class,
        ]);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function staticPages(): array
    {
        return [
            'home' => ['home'],
            'sejarah' => ['sejarah'],
            'visi-misi' => ['visi-misi'],
            'struktur-organisasi' => ['struktur-organisasi'],
            'sarana-prasarana' => ['sarana-prasarana.index'],
            'program-keahlian' => ['program-keahlian.index'],
            'berita' => ['berita.index'],
            'prestasi' => ['prestasi'],
            'download' => ['download'],
            'ekstrakurikuler' => ['ekstrakurikuler'],
            'organisasi-siswa' => ['organisasi-siswa'],
            'blud' => ['blud'],
            'pkl' => ['pkl'],
            'ppdb' => ['ppdb'],
        ];
    }

    #[DataProvider('staticPages')]
    public function test_public_page_renders_with_site_content(string $routeName): void
    {
        $this->seedSite();

        $this->get(route($routeName))->assertOk();
    }

    public function test_home_page_shows_principal_message_from_settings(): void
    {
        $this->seedSite();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Assalamualaikum warahmatullahi wabarakatuh', false);
    }

    public function test_saving_a_setting_is_visible_to_the_page_rendered_in_the_same_request(): void
    {
        $this->seedSite();

        // allCached() menyalin hasil baca selama satu request supaya halaman
        // tidak query tabel cache puluhan kali. Risikonya: nilai lama ikut
        // terbawa setelah admin menyimpan. Test ini menjaga agar pembacaan
        // sesudah penyimpanan selalu melihat nilai baru.
        $this->assertStringStartsWith(
            'Assalamualaikum warahmatullahi wabarakatuh',
            (string) SiteSetting::value('principal.message')
        );

        SiteSetting::query()
            ->where('key', 'principal.message')
            ->firstOrFail()
            ->update(['value' => 'Bismillahirrahmanirrahim']);

        $this->assertSame(
            'Bismillahirrahmanirrahim',
            SiteSetting::value('principal.message')
        );

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Bismillahirrahmanirrahim', false)
            ->assertDontSee('Assalamualaikum warahmatullahi wabarakatuh', false);
    }

    public function test_home_page_lists_every_competition_organizer_with_a_link_to_their_site(): void
    {
        $this->seedSite();

        $response = $this->get(route('home'))->assertOk();

        $response->assertSee('Support By');

        foreach (['jhic', 'jagoan-hosting', 'komdigi', 'garuda-spark', 'ngalup'] as $logo) {
            $response->assertSee("images/penyelenggara/{$logo}.png", escape: false);
        }

        foreach ([
            'https://www.jagoanhosting.com/',
            'https://www.komdigi.go.id/',
            'https://garudaspark.id/',
            'https://ngalup.co/',
        ] as $url) {
            $response->assertSee($url, escape: false);
        }
    }

    public function test_home_page_renders_an_organizer_without_a_link_when_its_scheme_is_not_allowed(): void
    {
        $this->seedSite();

        Gallery::create([
            'image' => 'images/penyelenggara/penyelenggara-tambahan.png',
            'website_url' => 'javascript:alert(1)',
            'alt' => 'Penyelenggara Tambahan',
            'group' => 'penyelenggara',
            'is_published' => true,
            'sort_order' => 99,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('images/penyelenggara/penyelenggara-tambahan.png', escape: false)
            ->assertDontSee('javascript:alert(1)', escape: false);
    }

    public function test_program_keahlian_detail_renders_for_published_program(): void
    {
        $this->seedSite();

        $program = ProgramKeahlian::published()->firstOrFail();

        $this->get(route('program-keahlian.show', $program))->assertOk();
    }

    public function test_navbar_lists_all_eight_programs_in_requested_order(): void
    {
        $this->seedSite();

        $expectedSlugs = [
            'rekayasa-perangkat-lunak',
            'teknik-komputer-dan-jaringan',
            'desain-komunikasi-visual',
            'akutansi-dan-lembaga-keuangan',
            'layanan-perbankan-syariah',
            'manajemen-perkantoran-dan-layanan-bisnis',
            'bisnis-ritel',
            'bisnis-digital',
        ];

        $this->assertSame(8, ProgramKeahlian::published()->count());

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(
                array_map(fn (string $slug): string => route('program-keahlian.show', $slug), $expectedSlugs),
                false
            );
    }

    public function test_blud_page_lists_every_production_unit(): void
    {
        $this->seedSite();

        $response = $this->get(route('blud'))->assertOk();

        foreach (BludUnit::published()->ordered()->get() as $unit) {
            $response->assertSee($unit->name);
        }

        $this->assertCount(9, BludUnit::published()->get());
    }

    public function test_blud_page_shows_images_on_some_cards(): void
    {
        $this->seedSite();
        $this->get(route('blud'))->assertOk()->assertSee('images/blud/skansabastore.jpeg', false);
    }

    public function test_ekstrakurikuler_shows_cards_for_all_items(): void
    {
        $this->seedSite();
        foreach (Ekstrakurikuler::published()->ordered()->get() as $item) {
            $this->get(route('ekstrakurikuler'))->assertSee($item->name);
        }
    }

    public function test_ekstrakurikuler_shows_image_paths_for_items_with_images(): void
    {
        $this->seedSite();
        foreach (Ekstrakurikuler::published()->ordered()->get() as $item) {
            if ($item->image_url) {
                $this->get(route('ekstrakurikuler'))->assertSee($item->image_url, false);
            }
        }
    }

    public function test_ekstrakurikuler_shows_placeholder_for_items_without_image(): void
    {
        $this->seedSite();
        $this->get(route('ekstrakurikuler'))->assertOk()->assertSee('Gambar belum tersedia');
    }

    public function test_pkl_page_lists_industry_partners_and_pkl_places_per_program(): void
    {
        $this->seedSite();

        $this->assertSame(6, TeachingFactory::published()->count());

        $response = $this->get(route('pkl'))
            ->assertOk()
            ->assertSee('Daftar Tempat PKL')
            ->assertSee('PT Diginet Media')
            ->assertSee('PT Gamatechno Indonesia')
            ->assertSee('Other departments');

        foreach (['rekayasa-perangkat-lunak', 'desain-komunikasi-visual'] as $slug) {
            $response->assertSee(ProgramKeahlian::where('slug', $slug)->firstOrFail()->title);
        }
    }

    public function test_teaching_factory_page_redirects_permanently_to_pkl(): void
    {
        $this->seedSite();

        $this->get('/teaching-factory')
            ->assertMovedPermanently()
            ->assertRedirect(route('pkl'));
    }

    public function test_ppdb_program_cards_link_to_their_own_program_page(): void
    {
        $this->seedSite();

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSeeInOrder([
                'Pengelolaan toko, pelayanan pelanggan, strategi penjualan, penataan produk',
                route('program-keahlian.show', 'bisnis-ritel'),
            ], false)
            ->assertSeeInOrder([
                'Digital marketing, e-commerce, pengelolaan konten, strategi bisnis online',
                route('program-keahlian.show', 'bisnis-digital'),
            ], false);
    }

    public function test_sarana_prasarana_detail_renders_for_published_item(): void
    {
        $this->seedSite();

        $sarana = SaranaPrasarana::published()->firstOrFail();

        $this->get(route('sarana-prasarana.show', $sarana))->assertOk();
    }

    public function test_berita_detail_renders_for_published_article(): void
    {
        $article = Article::factory()->create(['title' => 'Juara LKS Nasional']);

        $this->get(route('berita.show', $article))
            ->assertOk()
            ->assertSee('Juara LKS Nasional');
    }

    public function test_berita_index_lists_seeded_articles_newest_first(): void
    {
        $this->seedSite();

        $this->assertSame(15, Article::published()->count());

        $this->get(route('berita.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'SMKN 1 Bantul Perkuat Kemitraan Industri',
                'SNBP 2026',
                'Prestasi LKS 2026',
            ]);
    }

    public function test_berita_detail_renders_seeded_article_with_image(): void
    {
        $this->seedSite();

        $article = Article::where('slug', 'prestasi-lks-2026')->firstOrFail();

        $this->get(route('berita.show', $article))
            ->assertOk()
            ->assertSee('Ilyas Giyan Swandaru')
            ->assertSee('images/berita/01KRD6DDVXY3G50NWN91E40WKQ.png', false);
    }

    public function test_download_page_lists_seeded_files(): void
    {
        $this->seedSite();

        $this->get(route('download'))
            ->assertOk()
            ->assertSee('Akreditasi Sekolah')
            ->assertSee('Pengumuman Eligible Seleksi SNBP Tahun 2026')
            ->assertSee('downloads/01KEXC79ASQ42X6NAWJA33Y6N6.pdf', false);
    }

    public function test_berita_detail_returns_404_for_unpublished_article(): void
    {
        $article = Article::factory()->unpublished()->create();

        $this->get(route('berita.show', $article))->assertNotFound();
    }

    public function test_berita_index_filters_articles_by_search_term(): void
    {
        Article::factory()->create(['title' => 'Prestasi Robotik Siswa']);
        Article::factory()->create(['title' => 'Jadwal Ujian Sekolah']);

        $this->get(route('berita.index', ['q' => 'Robotik']))
            ->assertOk()
            ->assertSee('Prestasi Robotik Siswa')
            ->assertDontSee('Jadwal Ujian Sekolah');
    }

    public function test_berita_index_does_not_list_unpublished_articles(): void
    {
        Article::factory()->create(['title' => 'Berita Terbit']);
        Article::factory()->unpublished()->create(['title' => 'Berita Arsip']);

        $this->get(route('berita.index'))
            ->assertOk()
            ->assertSee('Berita Terbit')
            ->assertDontSee('Berita Arsip');
    }

    public function test_sitemap_lists_published_content_as_xml(): void
    {
        $this->seedSite();

        $program = ProgramKeahlian::published()->firstOrFail();

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('program-keahlian.show', $program), false);
    }

    public function test_robots_disallows_admin_and_links_sitemap(): void
    {
        $this->get(route('robots'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.route('sitemap'));
    }
}
