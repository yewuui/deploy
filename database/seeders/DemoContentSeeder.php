<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $akl = Program::where('slug', 'akl')->first();

        if ($akl) {
            $akl->curriculums()->firstOrCreate(
                ['program_id' => $akl->id, 'title' => '[Contoh] Pengantar Akuntansi Dasar'],
                ['grade' => 10, 'semester' => 1, 'description' => 'Data contoh untuk pengujian CMS. Ubah atau hapus dari halaman admin.', 'topics' => ['Persamaan Dasar Akuntansi', 'Jurnal Umum', 'Buku Besar'], 'week_start' => 1, 'week_end' => 2, 'color_theme' => 'blue', 'order_number' => 1]
            );
            $akl->curriculums()->firstOrCreate(
                ['program_id' => $akl->id, 'title' => '[Contoh] Siklus Akuntansi Jasa'],
                ['grade' => 10, 'semester' => 2, 'description' => 'Data contoh untuk pengujian CMS. Ubah atau hapus dari halaman admin.', 'topics' => ['Neraca Saldo', 'Jurnal Penyesuaian', 'Laporan Keuangan'], 'week_start' => 1, 'week_end' => 2, 'color_theme' => 'blue', 'order_number' => 1]
            );
            $akl->careers()->firstOrCreate(
                ['program_id' => $akl->id, 'title' => '[Contoh] Staf Akuntansi'],
                ['short_description' => 'Data contoh karier untuk pengujian CMS.', 'details' => 'Contoh deskripsi pekerjaan harian. Ganti dengan data resmi dari halaman admin.', 'tasks' => ['Mencatat transaksi harian', 'Menyusun laporan sederhana'], 'tools' => ['Spreadsheet', 'Aplikasi Akuntansi'], 'icon' => 'checklist', 'order_number' => 1, 'is_active' => true]
            );
            $akl->achievements()->firstOrCreate(
                ['program_id' => $akl->id, 'title' => '[Contoh] Juara Lomba Akuntansi'],
                ['name' => 'Tim Contoh', 'date_label' => '2026', 'category' => 'Kompetisi', 'level' => 'Sekolah', 'description' => 'Data contoh untuk pengujian CMS.', 'order_number' => 1, 'is_active' => true]
            );
            $akl->media()->firstOrCreate(
                ['program_id' => $akl->id, 'platform' => 'Instagram'],
                ['handle' => '@contoh.akl', 'description' => 'Data contoh kanal media untuk pengujian CMS.', 'url' => null, 'order_number' => 1, 'is_active' => true]
            );
        }

        $dkv = Program::where('slug', 'dkv')->first();

        if ($dkv) {
            $dkv->curriculums()->firstOrCreate(
                ['program_id' => $dkv->id, 'title' => '[Contoh] Dasar Desain Grafis'],
                ['grade' => 10, 'semester' => 1, 'description' => 'Data contoh untuk pengujian CMS. Ubah atau hapus dari halaman admin.', 'topics' => ['Elemen Desain', 'Warna dan Tipografi', 'Komposisi'], 'week_start' => 1, 'week_end' => 2, 'color_theme' => 'blue', 'order_number' => 1]
            );
            $dkv->careers()->firstOrCreate(
                ['program_id' => $dkv->id, 'title' => '[Contoh] Desainer Grafis'],
                ['short_description' => 'Data contoh karier untuk pengujian CMS.', 'details' => 'Contoh deskripsi pekerjaan harian. Ganti dengan data resmi dari halaman admin.', 'tasks' => ['Membuat layout sederhana', 'Mengolah gambar'], 'tools' => ['Aplikasi Desain'], 'icon' => 'pen', 'order_number' => 1, 'is_active' => true]
            );
        }
    }
}
