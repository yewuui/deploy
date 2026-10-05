<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\OrganisasiSiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class GalleryPageController extends Controller
{
    public function ekstrakurikuler(): View
    {
        return view('ekstrakurikuler', [
            'rows' => $this->numberedRows(Ekstrakurikuler::published()->ordered()->get()),
        ]);
    }

    public function organisasiSiswa(): View
    {
        return view('organisasi-siswa', [
            'rows' => $this->numberedRows(OrganisasiSiswa::published()->ordered()->get()),
        ]);
    }

    /**
     * Ubah koleksi menjadi baris tabel bernomor untuk komponen x-data-table.
     *
     * Query dijalankan sekali di caller: sebelumnya setiap method mengambil
     * koleksi yang sama dua kali, dan hasil pertama (`items`) tidak pernah
     * dipakai di view sehingga selalu menjadi query sia-sia.
     *
     * @param  Collection<int, Ekstrakurikuler|OrganisasiSiswa>  $items
     * @return list<array{0: int, 1: string}>
     */
    private function numberedRows(Collection $items): array
    {
        return $items
            ->values()
            ->map(fn ($item, $i) => [$i + 1, $item->name])
            ->all();
    }
}
