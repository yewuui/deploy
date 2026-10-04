<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramKeahlian;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Pemetaan slug program EduRoute ke slug program keahlian di situs sekolah.
     * Dipakai navbar supaya menu "Program Kepertise" membuka halaman EduRoute.
     *
     * @var array<string, string>
     */
    private array $programKeahlianSlugs = [
        'rpl' => 'rekayasa-perangkat-lunak',
        'akl' => 'akutansi-dan-lembaga-keuangan',
        'bd' => 'bisnis-digital',
        'br' => 'bisnis-ritel',
        'dkv' => 'desain-komunikasi-visual',
        'lps' => 'layanan-perbankan-syariah',
        'mp' => 'manajemen-perkantoran-dan-layanan-bisnis',
        'tkj' => 'teknik-komputer-dan-jaringan',
    ];

    private function programKeahlianIdFor(string $programSlug): ?int
    {
        $slug = $this->programKeahlianSlugs[$programSlug] ?? null;

        return $slug ? ProgramKeahlian::where('slug', $slug)->value('id') : null;
    }

    public function run(): void
    {
        $programs = [
            [
                'name' => 'Rekayasa Perangkat Lunak',
                'short_name' => 'RPL',
                'slug' => 'rpl',
                'description' => 'Program keahlian yang mempelajari pengembangan perangkat lunak, mulai dari merancang, membuat kode, menguji aplikasi, hingga mengelola basis data.',
            ],
            [
                'name' => 'Akuntansi',
                'short_name' => 'AKL',
                'slug' => 'akl',
                'description' => 'Program Keahlian Akuntansi SMKN 1 Bantul.',
            ],
            [
                'name' => 'Bisnis Digital',
                'short_name' => 'BD',
                'slug' => 'bd',
                'description' => 'Program Keahlian Bisnis Digital SMKN 1 Bantul.',
            ],
            [
                'name' => 'Bisnis Retail',
                'short_name' => 'BR',
                'slug' => 'br',
                'description' => 'Program Keahlian Bisnis Retail SMKN 1 Bantul.',
            ],
            [
                'name' => 'Desain Komunikasi Visual',
                'short_name' => 'DKV',
                'slug' => 'dkv',
                'description' => 'Program Keahlian Desain Komunikasi Visual SMKN 1 Bantul.',
            ],
            [
                'name' => 'Layanan Perbankan Syariah',
                'short_name' => 'LPS',
                'slug' => 'lps',
                'description' => 'Program Keahlian Layanan Perbankan Syariah SMKN 1 Bantul.',
            ],
            [
                'name' => 'Manajemen Perkantoran',
                'short_name' => 'MP',
                'slug' => 'mp',
                'description' => 'Program Keahlian Manajemen Perkantoran SMKN 1 Bantul.',
            ],
            [
                'name' => 'Teknik Komputer dan Jaringan',
                'short_name' => 'TKJ',
                'slug' => 'tkj',
                'description' => 'Program Keahlian Teknik Komputer dan Jaringan SMKN 1 Bantul.',
            ],
        ];

        foreach ($programs as $item) {
            $item['program_keahlian_id'] = $this->programKeahlianIdFor($item['slug']);

            Program::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['is_active' => true]));
        }

        $rpl = Program::where('slug', 'rpl')->first();

        if ($rpl && ! $rpl->hero_text) {
            $rpl->update([
                'hero_title' => 'Rekayasa Perangkat Lunak (RPL)',
                'hero_text' => 'Jurusan yang mempelajari pengembangan perangkat lunak — mulai dari merancang, membuat kode, menguji aplikasi, hingga mengelola basis data. Siswa belajar membangun website, aplikasi mobile, dan antarmuka yang mudah digunakan melalui praktik langsung di kelas.',
                'highlights' => [
                    ['title' => 'Pengembangan Web', 'description' => 'Membangun website modern dari Frontend hingga Backend menggunakan framework industri terkini.'],
                    ['title' => 'Aplikasi Mobile', 'description' => 'Merancang dan mengembangkan aplikasi Android/iOS yang interaktif dan ramah pengguna.'],
                    ['title' => 'Manajemen Basis Data', 'description' => 'Merancang struktur penyimpanan data yang aman, efisien, dan cepat untuk skala besar.'],
                    ['title' => 'UI/UX Design', 'description' => 'Mendesain antarmuka visual aplikasi yang estetis dan menciptakan pengalaman pengguna yang nyaman.'],
                ],
            ]);
        }
    }
}
