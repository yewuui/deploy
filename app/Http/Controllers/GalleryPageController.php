<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\OrganisasiSiswa;
use Illuminate\Contracts\View\View;

class GalleryPageController extends Controller
{
    public function ekstrakurikuler(): View
    {
        return view('ekstrakurikuler', [
            'items' => Ekstrakurikuler::published()->ordered()->get(),
        ]);
    }

    public function organisasiSiswa(): View
    {
        return view('organisasi-siswa', [
            'items' => OrganisasiSiswa::published()->ordered()->get(),
        ]);
    }
}
