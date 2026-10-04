@extends('layouts.app')

@php
    use App\Support\Site;
    use Illuminate\Support\Str;

    $seoTitle = 'PKL';
    $seoDescription = 'Kemitraan Praktik Kerja Lapangan '.\App\Support\Site::name().' bersama industri untuk memberi pengalaman kerja nyata bagi siswa.';
@endphp

@section('content')
    <x-page-title text="Praktik Kerja Lapangan"
                  :description="'Mitra industri dan daftar tempat PKL untuk setiap program keahlian '.Site::name().'.'">
        <dl class="flex flex-wrap gap-3">
            <div class="rounded-xl bg-white px-5 py-3 ring-1 ring-slate-900/5">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Mitra Industri</dt>
                <dd class="mt-0.5 font-display text-2xl font-bold text-slate-900">{{ $partners->count() }}</dd>
            </div>
            <div class="rounded-xl bg-white px-5 py-3 ring-1 ring-slate-900/5">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Tempat PKL</dt>
                <dd class="mt-0.5 font-display text-2xl font-bold text-slate-900">{{ $placeCount }}</dd>
            </div>
            <div class="rounded-xl bg-white px-5 py-3 ring-1 ring-slate-900/5">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Program Keahlian</dt>
                <dd class="mt-0.5 font-display text-2xl font-bold text-slate-900">{{ $places->count() }}</dd>
            </div>
        </dl>
    </x-page-title>

    <div class="container-page max-w-6xl py-10 lg:py-14">
        <x-breadcrumbs :breadcrumbs="[['label' => 'Layanan'], ['label' => 'PKL']]" />

        <div class="mt-6 rounded-2xl bg-amber-50 p-4 ring-1 ring-amber-200">
            <p class="text-sm text-amber-800"><span class="font-semibold">Catatan (internal):</span> Data pada halaman ini bersifat <em>contoh/dummy</em> dan ditandai. Siapkan data aktual untuk diisi kembali.</p>
        </div>



        <section class="mt-16 lg:mt-20" aria-labelledby="tempat-pkl-heading">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="eyebrow">Penempatan Siswa</p>
                    <h2 id="tempat-pkl-heading" class="mt-2 font-display text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Daftar Tempat PKL
                    </h2>
                </div>
                <p class="text-sm text-slate-500">* Data contoh (dummy) — dapat diganti sesuai data aktual</p>
            </div>

            <p class="mt-3 max-w-2xl leading-relaxed text-slate-600">
                Berikut daftar penempatan PKL untuk RPL (Rekayasa Perangkat Lunak) dengan hanya menampilkan nama perusahaan/lembaga dan lokasi.
            </p>

            <article class="mt-8 flex flex-col overflow-hidden rounded-2xl bg-white shadow-card ring-1 ring-slate-900/5" data-reveal="up">
                <header class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-surface px-5 py-4 sm:px-6">
                    <h3 class="font-display text-base font-bold leading-snug text-slate-900">
                        Rekayasa Perangkat Lunak (RPL)
                    </h3>
                    <span class="badge badge-info shrink-0">25 tempat <span class="ml-1 text-slate-400" aria-hidden="true">*</span></span>
                </header>

                <ul class="divide-y divide-slate-100 px-5 sm:px-6">
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">1. PT Diginet Media</span>
                        <span class="text-sm text-slate-600">Jalan Ring Road Utara, Jl. Tegal Mraen, RT.RW 05/RW.10, Mlati Dukuh, Sendangadi, Kec. Mlati, Kabupaten Sleman, DIY 55285</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">2. PT Gamatechno Indonesia</span>
                        <span class="text-sm text-slate-600">UGM Science Techno Park – Jl. Purwomartani, Kalasan, Sleman, Daerah Istimewa Yogyakarta 55571</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">3. PT Kita Kasih</span>
                        <span class="text-sm text-slate-600">Jl. Manding Imogiri, Ponggok I, Sumberagung, Kec. Jetis, Kabupaten Bantul, DIY 55781</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">4. Lembaga Sistem Informasi - Universitas Muhammadiyah Yogyakarta</span>
                        <span class="text-sm text-slate-600">Jl. Brawijaya, Geblagan, Tamantirto, Kec. Kasihan, Kabupaten Bantul, DIY 55183</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">5. PT Global Intermedia Nusantara</span>
                        <span class="text-sm text-slate-600">Jl. Taman Siswa No.125, Wirogunan, Kec. Mergangsan, Kota Yogyakarta, DIY 55151</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">6. PT Farma Global Teknologi</span>
                        <span class="text-sm text-slate-600">Jl. Magelang Km.12, Tridadi, Sleman, DIY</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">7. CV Estetika Indonesia</span>
                        <span class="text-sm text-slate-600">Jl. Parangtritis Km.9 No.5, Gatak, Timbulharjo, Sewon, Bantul, DIY</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">8. PT Intersolusi Cipta Softindo</span>
                        <span class="text-sm text-slate-600">Jl. Kompleks myRoom near UMY, Jl. Patriot Bangsa No.88, Tamantirto, Kasihan, Bantul, DIY</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">9. PT Indo Techno Medic</span>
                        <span class="text-sm text-slate-600">Jl. Menayu Lor, Tirtonirmolo, Kec. Kasihan, Kabupaten Bantul, DIY 55184</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">10. PT Lumut Karya Sejahtera</span>
                        <span class="text-sm text-slate-600">Jl. Bantul KM 8.5, Pendowoharjo, Sewon, Kabupaten Bantul, DIY 55186</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">11. Jogja Drumband</span>
                        <span class="text-sm text-slate-600">Jl. Manding Imogiri, Sabdodadi, Bantul</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">12. CV Creativemu</span>
                        <span class="text-sm text-slate-600">Perum Griya Kencana Permai Blok C4 No.1, Argorejo, Sedayu, Bantul, DIY 55752</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">13. CV Andi Global Soft</span>
                        <span class="text-sm text-slate-600">Jl. Beo No.38-40, Mrican, Caturtunggal, Depok, Sleman, DIY 55281</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">14. CV Sinergi Telematika</span>
                        <span class="text-sm text-slate-600">Kaliajir Lor, RT.07/RW.13, Kalitirto, Berbah, Sleman, DIY 55573</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">15. Bank Pasar Bantul</span>
                        <span class="text-sm text-slate-600">Kompleks Pasar Bantul, Kurahan, Bantul, DIY 55711</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">16. CV Bursa Media Group</span>
                        <span class="text-sm text-slate-600">Jl. Dayu Baru No.31, Tambakan, Sinduharjo, Ngaglik, Sleman, DIY 55581</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">17. PT Global Prima Utama (UIINET)</span>
                        <span class="text-sm text-slate-600">Jl. Cik Di Tiro No.1, Terban, Gondokusuman, Kota Yogyakarta, DIY 55223</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">18. ESODA (Era Solusi Data)</span>
                        <span class="text-sm text-slate-600">Jl. Ahmad Wahid No.172 B, Banguntapan, Bantul, Yogyakarta</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">19. PT Global Eushanosoft</span>
                        <span class="text-sm text-slate-600">Jl. Gedong Kuning No.202G, Pelem Mulong, Rejowinangun, Kotagede, Yogyakarta</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">20. PT Lauwba Techno Indonesia</span>
                        <span class="text-sm text-slate-600">Jl. Taman Kenari No.A3, Kledokan, Caturtunggal, Depok, Sleman, DIY 55281</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">21. Dinas Kominfo Gunungkidul</span>
                        <span class="text-sm text-slate-600">Purbosari, Alun-Alun, Wonosari, Gunungkidul, DIY 55851</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">22. CV Inolabs Indonesia</span>
                        <span class="text-sm text-slate-600">Jl. Pertapaan, Manggisan, Baturetno, Banguntapan, Bantul, DIY 55197</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">23. Dinas Kominfo Yogyakarta</span>
                        <span class="text-sm text-slate-600">Jl. Brigjen Katamso, Keparakan, Mergangsan, Yogyakarta</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">24. PT EDUWORK.ID</span>
                        <span class="text-sm text-slate-600">Jl. Ringroad Utara No.24, Yogyakarta</span>
                    </li>
                    <li class="flex flex-col gap-1 py-3 text-[15px] leading-snug text-slate-700">
                        <span class="font-medium text-slate-900">25. Other departments</span>
                        <span class="text-sm text-slate-600">-</span>
                    </li>
                </ul>
                <p class="px-5 py-3 text-xs text-slate-500 sm:px-6">* Data contoh (dummy) untuk RPL - hanya ditampilkan nama perusahaan/lembaga dan lokasi</p>
            </article>
        </section>


    </div>
@endsection
