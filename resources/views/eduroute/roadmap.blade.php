@extends('layouts.eduroute', ['activeMenu' => 'roadmap'])

@php
    $seoTitle = 'Roadmap '.$program->short_name.' Kelas '.$activeGrade;
    $seoDescription = 'Roadmap pembelajaran kelas '.$activeGrade.' program keahlian '.$program->name.' SMKN 1 Bantul.';
@endphp

@section('content')
@php
    // Data tahap berasal dari controller (sudah ter-scoping program, kelas,
    // dan terurut order_number). Angka tahap hanya penanda urutan, bukan jadwal.
    $gradePhases = [
        10 => ['label' => 'Fondasi', 'intro' => 'Memahami dasar komputer, logika, pemrograman, dan teknologi yang menjadi dasar pengembangan software.'],
        11 => ['label' => 'Pengembangan', 'intro' => 'Tahap pengembangan: memperdalam keahlian jurusan.'],
        12 => ['label' => 'Proyek dan Kesiapan', 'intro' => 'Tahap akhir perjalanan: proyek dan kesiapan kerja.'],
    ];
    
    // Custom content for Grade 12 Project/UKK
    $isGrade12 = $activeGrade === 12;
    $progName = strtolower($program->short_name ?? '');
    $progSlug = strtolower($program->slug ?? '');
    $isRpl = $progName === 'rpl' || $progSlug === 'rpl';
    $gradePhase = $gradePhases[$activeGrade] ?? ['label' => null, 'intro' => 'Perjalanan belajar kelas ini akan tampil di sini setelah data ditambahkan.'];

    // Palet accent terkontrol: satu warna solid per tahap, dipakai hanya pada
    // garis rel, stem penghubung, top border card, node (dot), dan nomor tahap.
    // Aksen tidak dipakai untuk CTA dan focus ring (tetap school blue).
    // Tanpa gradient, glow, atau glassmorphism.
    $accents = [
        ['bar' => 'border-t-blue-500', 'line' => 'bg-blue-500', 'dot' => 'bg-blue-500', 'nodeText' => 'text-white', 'number' => 'text-blue-800 dark:text-blue-300', 'hover' => 'hover:border-blue-300 dark:hover:border-blue-700', 'soft' => 'group-hover:bg-blue-100 dark:group-hover:bg-blue-900/60'],
        ['bar' => 'border-t-teal-500', 'line' => 'bg-teal-500', 'dot' => 'bg-teal-500', 'nodeText' => 'text-white', 'number' => 'text-teal-800 dark:text-teal-300', 'hover' => 'hover:border-teal-300 dark:hover:border-teal-700', 'soft' => 'group-hover:bg-teal-100 dark:group-hover:bg-teal-900/60'],
        ['bar' => 'border-t-green-500', 'line' => 'bg-green-500', 'dot' => 'bg-green-500', 'nodeText' => 'text-white', 'number' => 'text-green-800 dark:text-green-300', 'hover' => 'hover:border-green-300 dark:hover:border-green-700', 'soft' => 'group-hover:bg-green-100 dark:group-hover:bg-green-900/60'],
        ['bar' => 'border-t-amber-500', 'line' => 'bg-amber-500', 'dot' => 'bg-amber-500', 'node' => 'bg-amber-700', 'nodeText' => 'text-white', 'number' => 'text-amber-800 dark:text-amber-300', 'hover' => 'hover:border-amber-300 dark:hover:border-amber-700', 'soft' => 'group-hover:bg-amber-100 dark:group-hover:bg-amber-900/60'],
        ['bar' => 'border-t-orange-500', 'line' => 'bg-orange-500', 'dot' => 'bg-orange-500', 'nodeText' => 'text-white', 'number' => 'text-orange-800 dark:text-orange-300', 'hover' => 'hover:border-orange-300 dark:hover:border-orange-700', 'soft' => 'group-hover:bg-orange-100 dark:group-hover:bg-orange-900/60'],
        ['bar' => 'border-t-purple-500', 'line' => 'bg-purple-500', 'dot' => 'bg-purple-500', 'nodeText' => 'text-white', 'number' => 'text-purple-800 dark:text-purple-300', 'hover' => 'hover:border-purple-300 dark:hover:border-purple-700', 'soft' => 'group-hover:bg-purple-100 dark:group-hover:bg-purple-900/60'],
    ];

    // Kanvas desktop adalah grid horizontal: tiap stage adalah 3 sel
    // se-kolom pada track 15rem sehingga lebar stabil untuk jumlah
    // stage berapa pun dalam satu alur kontinu per kelas.
@endphp

<div class="mx-auto w-full min-w-0 max-w-[1200px]">
    <div class="mb-4 max-w-3xl" data-reveal="up">
        <div class="inline-flex flex-col items-start">
            <h2 class="text-h2 font-semibold text-ink dark:text-night-ink">
                Kelas {{ $activeGrade }}@if($gradePhase['label']) <span class="font-medium text-muted dark:text-night-muted">&mdash; {{ $gradePhase['label'] }}</span>@endif
            </h2>
            <span class="mt-2.5 block h-0.5 w-full min-w-[4rem] rounded-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
        </div>
        <p class="mt-3 text-small text-muted dark:text-night-muted">
            @if($isGrade12 && $isRpl)
                Gambaran umum kegiatan proyek yang dikerjakan siswa kelas 12 RPL.
            @else
                {{ $gradePhase['intro'] }}
            @endif
        </p>
    </div>

    @if($totalStages === 0 && !($isGrade12 && $isRpl))
        <div class="max-w-3xl rounded-card border border-hairline bg-paper p-10 text-center shadow-soft dark:border-night-hairline dark:bg-night-surface" data-reveal="up">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-small font-semibold text-ink dark:text-night-ink">Segera Hadir</p>
        </div>
    @elseif($isGrade12 && $isRpl)
        <div class="mb-8 max-w-4xl" data-reveal="up">
            <h3 class="text-h3 font-semibold text-ink dark:text-night-ink">Project / UKK Kelas 12 RPL</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-card border border-hairline bg-surface p-4 shadow-soft transition duration-200 hover:-translate-y-0.5 hover:shadow-card dark:border-night-hairline dark:bg-night-raised">
                    <h4 class="text-base font-semibold text-ink dark:text-night-ink">Web development</h4>
                    <p class="mt-1 text-micro text-muted dark:text-night-muted">Pengembangan aplikasi dan situs berbasis web sesuai kebutuhan proyek.</p>
                </div>
                <div class="rounded-card border border-hairline bg-surface p-4 shadow-soft transition duration-200 hover:-translate-y-0.5 hover:shadow-card dark:border-night-hairline dark:bg-night-raised">
                    <h4 class="text-base font-semibold text-ink dark:text-night-ink">Application development</h4>
                    <p class="mt-1 text-micro text-muted dark:text-night-muted">Pengembangan aplikasi desktop atau mobile untuk solusi sederhana.</p>
                </div>
                <div class="rounded-card border border-hairline bg-surface p-4 shadow-soft transition duration-200 hover:-translate-y-0.5 hover:shadow-card dark:border-night-hairline dark:bg-night-raised">
                    <h4 class="text-base font-semibold text-ink dark:text-night-ink">Game development</h4>
                    <p class="mt-1 text-micro text-muted dark:text-night-muted">Pembuatan game edukatif atau game sederhana sebagai proyek akhir.</p>
                </div>
                <div class="rounded-card border border-hairline bg-surface p-4 shadow-soft transition duration-200 hover:-translate-y-0.5 hover:shadow-card dark:border-night-hairline dark:bg-night-raised">
                    <h4 class="text-base font-semibold text-ink dark:text-night-ink">Information system / software projects</h4>
                    <p class="mt-1 text-micro text-muted dark:text-night-muted">Pengembangan sistem informasi atau proyek perangkat lunak terintegrasi.</p>
                </div>
            </div>
        </div>
    @else
        {{-- Toolbar navigasi kanvas (desktop): tombol geser dipusatkan --}}
        <div class="mb-4 hidden w-full lg:flex items-center justify-center gap-4" data-reveal="up" data-reveal-delay="100">
            <p id="roadmap-hint" class="sr-only">Geser untuk melihat tahap berikutnya <span aria-hidden="true">&rarr;</span></p>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="roadmap-prev"
                    aria-label="Geser roadmap ke kiri"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-item border border-royal/50 bg-royal-soft text-royal-strong shadow-soft transition-colors duration-200 hover:border-royal hover:bg-royal hover:text-white dark:border-night-hairline dark:bg-night-surface dark:text-night-muted dark:hover:border-night-royal/40 dark:hover:bg-night-raised dark:hover:text-night-royal focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-canvas disabled:opacity-40 disabled:pointer-events-none"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button
                    type="button"
                    id="roadmap-next"
                    aria-label="Geser roadmap ke kanan"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-item border border-royal/50 bg-royal-soft text-royal-strong shadow-soft transition-colors duration-200 hover:border-royal hover:bg-royal hover:text-white dark:border-night-hairline dark:bg-night-surface dark:text-night-muted dark:hover:border-night-royal/40 dark:hover:bg-night-raised dark:hover:text-night-royal focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-canvas disabled:opacity-40 disabled:pointer-events-none"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        {{-- Kanvas horizontal: SATU jalur pembelajaran kontinu per kelas.
            SATU grid bersama (grid-flow-col) dengan 3 track baris yang
            dibagi semua kolom: zona atas / rel node / zona bawah. Semua
            node duduk di track rel yang sama -> satu garis lurus sempurna,
            berapa pun tinggi kartu dan jumlah stage. Tiap stage menempati
            3 sel se-kolom via auto-placement dari satu loop datar (tanpa
            segmentasi semester, tanpa counter kolom manual). Satu label
            overlay kecil (absolute, zero flow) menandai awal perjalanan.
            Tinggi kartu mengikuti content. Tanpa gradient/glow. --}}
        <section aria-label="Roadmap pembelajaran kelas {{ $activeGrade }}" class="hidden lg:block min-w-0" data-reveal="zoom">
            <div
                id="roadmap-scroll"
                tabindex="0"
                role="region"
                aria-label="Jalur pembelajaran horizontal. Geser untuk melihat tahap berikutnya."
                class="overflow-x-auto pb-4 cursor-grab min-w-0"
            >
                <div id="roadmap-canvas" class="grid w-max grid-flow-col gap-0 pt-12"
                    style="grid-template-rows: minmax(220px, 1fr) auto minmax(220px, 1fr); grid-template-columns: repeat({{ max($stages->count(), 1) }}, 15rem);">
                    @php $stageNumber = 1; @endphp

                    {{-- Perjalanan kontinu: satu loop datar untuk seluruh kelas, diurutkan order_number. --}}
                    @if($stages->isEmpty())
                        <div class="flex min-w-0 flex-col justify-end px-3">
                            <p class="text-small text-muted dark:text-night-muted border border-dashed border-hairline dark:border-night-hairline rounded-card px-4 py-3">Belum ada materi untuk kelas ini.</p>
                        </div>
                        <div class="flex h-10 min-w-0 items-center" aria-hidden="true">
                            <span class="h-[3px] w-full bg-hairline dark:bg-night-hairline"></span>
                        </div>
                        <div class="min-w-0" aria-hidden="true"></div>
                    @else
                        @foreach ($stages as $item)
                            @php
                                $stageLabel = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                                $accent = $accents[($loop->iteration - 1) % count($accents)];
                                $isTop = $loop->iteration % 2 === 1;
                            @endphp
                            @if($isTop)
                                <div class="relative flex min-w-0 flex-col justify-end px-3">
                                    @if($loop->first)
                                        <div class="absolute -top-10 left-3 right-3 flex h-6 items-center gap-3" aria-hidden="true">
                                            <span class="h-2 w-2 shrink-0 rounded-full bg-royal dark:bg-night-royal"></span>
                                            <p class="text-micro font-semibold text-muted dark:text-night-muted whitespace-nowrap">{{ $totalStages }} {{ $activeGrade === 12 ? 'kegiatan' : 'tahap' }} &middot; Kelas {{ $activeGrade }}</p>
                                            <span class="h-px flex-1 bg-hairline dark:bg-night-hairline"></span>
                                        </div>
                                    @endif
                                    <x-roadmap-stage-card :stageLabel="$stageLabel" :title="$item->title" :description="$item->description" :topics="$item->topics" :typeLabel="$activeGrade === 12 ? $item->type_label : null" :imageUrl="null" :accent="$accent" :bar="$accent['bar']" class="cursor-pointer duration-200" />
                                    <span class="mx-auto mt-2.5 h-6 w-[3px] rounded-full {{ $accent['line'] }}" aria-hidden="true"></span>
                                </div>
                                <div class="flex h-10 min-w-0 items-center" aria-hidden="true">
                                    <span class="relative flex w-full items-center">
                                        <span class="h-[3px] w-full {{ $accent['line'] }}"></span>
                                        <span class="absolute left-1/2 -translate-x-1/2 flex h-8 w-8 items-center justify-center rounded-full border-2 border-paper dark:border-night-canvas {{ $accent['node'] ?? $accent['dot'] }} text-[11px] font-bold tabular-nums {{ $accent['nodeText'] }}">{{ $stageLabel }}</span>
                                    </span>
                                </div>
                                <div class="min-w-0" aria-hidden="true"></div>
                            @else
                                <div class="relative min-w-0">
                                    @if($loop->first)
                                        <div class="absolute -top-10 left-3 right-3 flex h-6 items-center gap-3" aria-hidden="true">
                                            <span class="h-2 w-2 shrink-0 rounded-full bg-royal dark:bg-night-royal"></span>
                                            <p class="text-micro font-semibold text-muted dark:text-night-muted whitespace-nowrap">{{ $totalStages }} tahap &middot; Kelas {{ $activeGrade }}</p>
                                            <span class="h-px flex-1 bg-hairline dark:bg-night-hairline"></span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex h-10 min-w-0 items-center" aria-hidden="true">
                                    <span class="relative flex w-full items-center">
                                        <span class="h-[3px] w-full {{ $accent['line'] }}"></span>
                                        <span class="absolute left-1/2 -translate-x-1/2 flex h-8 w-8 items-center justify-center rounded-full border-2 border-paper dark:border-night-canvas {{ $accent['node'] ?? $accent['dot'] }} text-[11px] font-bold tabular-nums {{ $accent['nodeText'] }}">{{ $stageLabel }}</span>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-col justify-start px-3">
                                    <span class="mx-auto mb-2.5 h-6 w-[3px] rounded-full {{ $accent['line'] }}" aria-hidden="true"></span>
                                    <x-roadmap-stage-card :stageLabel="$stageLabel" :title="$item->title" :description="$item->description" :topics="$item->topics" :typeLabel="$activeGrade === 12 ? $item->type_label : null" :imageUrl="null" :accent="$accent" :bar="$accent['bar']" class="cursor-pointer duration-200" />
                                </div>
                            @endif
                        @endforeach
                    @endif

                    {{-- Segmentasi semester dihapus dari presentasi publik: satu alur kontinu per kelas. --}}
                </div>
            </div>
        </section>

        {{-- Mobile: vertical learning journey --}}
        <div class="lg:hidden">
            <section aria-label="Tahapan kelas {{ $activeGrade }}" class="mb-8">
                <div class="flex items-center gap-3 mb-5" data-reveal="up">
                    <span class="h-2 w-2 shrink-0 rounded-full bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                    <h3 class="text-small font-semibold text-ink dark:text-night-ink whitespace-nowrap">
                        {{ $activeGrade === 12 ? 'Program Kelas 12' : 'Perjalanan Kelas ' . $activeGrade }}
                        <span class="ml-1 font-medium text-muted dark:text-night-muted">{{ $totalStages }} {{ $activeGrade === 12 ? 'kegiatan' : 'tahap' }}</span>
                    </h3>
                    <span class="h-px flex-1 bg-hairline dark:bg-night-hairline" aria-hidden="true"></span>
                </div>

                @if($stages->isEmpty() && !($isGrade12 && $isRpl))
                    <p class="text-small text-muted dark:text-night-muted border border-dashed border-hairline dark:border-night-hairline rounded-card px-5 py-4" data-reveal="up">Belum ada materi untuk kelas ini.</p>
                @elseif($isGrade12 && $isRpl)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2" data-reveal="up">
                        <div class="rounded-card border border-hairline bg-surface p-4 shadow-soft dark:border-night-hairline dark:bg-night-raised">
                            <h4 class="text-base font-semibold text-ink dark:text-night-ink">Web development</h4>
                            <p class="mt-1 text-micro text-muted dark:text-night-muted">Pengembangan aplikasi dan situs berbasis web sesuai kebutuhan proyek.</p>
                        </div>
                        <div class="rounded-card border border-hairline bg-surface p-4 shadow-soft dark:border-night-hairline dark:bg-night-raised">
                            <h4 class="text-base font-semibold text-ink dark:text-night-ink">Application development</h4>
                            <p class="mt-1 text-micro text-muted dark:text-night-muted">Pengembangan aplikasi desktop atau mobile untuk solusi sederhana.</p>
                        </div>
                        <div class="rounded-card border border-hairline bg-surface p-4 shadow-soft dark:border-night-hairline dark:bg-night-raised">
                            <h4 class="text-base font-semibold text-ink dark:text-night-ink">Game development</h4>
                            <p class="mt-1 text-micro text-muted dark:text-night-muted">Pembuatan game edukatif atau game sederhana sebagai proyek akhir.</p>
                        </div>
                        <div class="rounded-card border border-hairline bg-surface p-4 shadow-soft dark:border-night-hairline dark:bg-night-raised">
                            <h4 class="text-base font-semibold text-ink dark:text-night-ink">Information system / software projects</h4>
                            <p class="mt-1 text-micro text-muted dark:text-night-muted">Pengembangan sistem informasi atau proyek perangkat lunak terintegrasi.</p>
                        </div>
                    </div>
                @else
                    <ol class="space-y-4" aria-label="Tahapan kelas {{ $activeGrade }}">
                        @foreach ($stages as $item)
                            @php
                                $mobileLabel = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                                $mobileAccent = $accents[($loop->iteration - 1) % count($accents)];
                            @endphp
                            <li class="relative min-w-0 pl-11" data-reveal="up" data-reveal-delay="{{ min(($loop->index % 4 + 1) * 75, 300) }}">
                                @if(!$loop->last)
                                    <span class="absolute -bottom-4 left-[21px] top-12 w-[3px] rounded-full {{ $mobileAccent['line'] }}" aria-hidden="true"></span>
                                @endif
                                <span class="absolute left-[8px] top-5 flex h-7 w-7 items-center justify-center rounded-full border-2 border-paper dark:border-night-canvas {{ $mobileAccent['node'] ?? $mobileAccent['dot'] }} text-[10px] font-bold tabular-nums {{ $mobileAccent['nodeText'] }}" aria-hidden="true">{{ $mobileLabel }}</span>
                                <x-roadmap-stage-card :stageLabel="$mobileLabel" :title="$item->title" :description="$item->description" :topics="$item->topics" :typeLabel="$activeGrade === 12 ? $item->type_label : null" :imageUrl="null" :accent="$mobileAccent" :bar="$mobileAccent['bar']" class="cursor-pointer duration-200" />
                            </li>
                        @endforeach
                    </ol>
                @endif
            </section>
        </div>

        <nav class="border-t border-hairline dark:border-night-hairline pt-6 pb-4" aria-label="Lanjutan perjalanan" data-reveal="up">
            @if($activeGrade < 12)
                <p class="text-micro font-semibold uppercase tracking-wider text-muted dark:text-night-muted">Perjalanan berlanjut</p>
                <p class="mt-1 text-small text-muted dark:text-night-muted">Setelah kelas {{ $activeGrade }}, perjalanan dilanjutkan ke kelas {{ $activeGrade + 1 }}.</p>
                <a href="{{ route('eduroute.roadmap', ['program' => $program, 'grade' => $activeGrade + 1]) }}" class="mt-3 inline-flex min-h-[44px] items-center gap-1.5 rounded-item text-small font-semibold text-royal-strong dark:text-night-royal transition-colors duration-200 hover:text-royal dark:hover:text-night-royal focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-canvas">
                    Lanjut ke Kelas {{ $activeGrade + 1 }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @else
                <p class="text-micro font-semibold uppercase tracking-wider text-muted dark:text-night-muted">Akhir perjalanan kelas</p>
                <p class="mt-1 text-small text-muted dark:text-night-muted">Setelah kelas 12, lihat arah karier yang bisa ditempuh lulusan {{ $program->short_name }}.</p>
                <a href="{{ route('eduroute.prospek', $program) }}" class="mt-3 inline-flex min-h-[44px] items-center gap-1.5 rounded-item text-small font-semibold text-royal-strong dark:text-night-royal transition-colors duration-200 hover:text-royal dark:hover:text-night-royal focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-canvas">
                    Lihat Prospek Karier
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @endif
        </nav>
    @endif
</div>

<div id="stage-modal-root" class="hidden fixed inset-0 z-[60]" aria-hidden="true">
    <div data-stage-backdrop class="absolute inset-0 bg-night-canvas/50 transition-opacity duration-150 ease-out opacity-0"></div>
    <div class="relative flex min-h-full items-end justify-center sm:items-center p-0 sm:p-6">
        <div
            id="stage-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="stage-modal-title"
            aria-describedby="stage-modal-description"
            tabindex="-1"
            class="relative w-full sm:max-w-lg rounded-t-hero sm:rounded-card border border-hairline dark:border-night-hairline border-t-[3px] bg-paper dark:bg-night-surface px-5 py-6 sm:p-6 shadow-lift max-h-[90vh] sm:max-h-[85vh] overflow-y-auto transition duration-150 ease-out opacity-0 scale-95 translate-y-2 sm:translate-y-0"
        >
            <p class="flex items-center gap-2 text-micro font-semibold text-royal-strong dark:text-night-royal">
                <span id="stage-modal-dot" class="h-2 w-2 shrink-0 rounded-full bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                <span id="stage-modal-kicker"></span>
            </p>
            <p id="stage-modal-type" class="mt-2 hidden text-micro font-semibold text-royal-strong dark:text-night-royal"></p>
            <h3 id="stage-modal-title" class="mt-1.5 text-h3 font-semibold leading-snug break-words text-ink dark:text-night-ink"></h3>
            <p id="stage-modal-description" class="mt-2 text-small text-muted dark:text-night-muted"></p>

            <p id="stage-modal-topics-label" class="mt-5 text-micro font-semibold text-ink dark:text-night-ink">Yang dipelajari</p>
            <ul id="stage-modal-topics" class="mt-2 text-small text-muted dark:text-night-muted list-disc pl-5 space-y-1.5 break-words"></ul>
            <button
                type="button"
                data-stage-close
                aria-label="Tutup dialog materi"
                class="absolute top-3 right-3 inline-flex h-11 w-11 items-center justify-center rounded-item text-muted transition-colors duration-200 hover:bg-surface hover:text-ink dark:text-night-muted dark:hover:bg-night-raised dark:hover:text-night-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    (function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- Kanvas horizontal: tombol geser, hint, drag ---------- */
        const scroller = document.getElementById('roadmap-scroll');
        const hint = document.getElementById('roadmap-hint');
        const prevBtn = document.getElementById('roadmap-prev');
        const nextBtn = document.getElementById('roadmap-next');

        if (scroller && prevBtn && nextBtn) {
            const scrollStep = 300;

            const updateScrollUI = function () {
                const maxScroll = scroller.scrollWidth - scroller.clientWidth;
                const hasOverflow = maxScroll > 1;

                if (hint) {
                    hint.classList.toggle('invisible', !hasOverflow);
                }

                prevBtn.disabled = !hasOverflow || scroller.scrollLeft <= 0;
                nextBtn.disabled = !hasOverflow || scroller.scrollLeft >= maxScroll - 1;
            };

            const scrollByStep = function (direction) {
                scroller.scrollBy({ left: direction * scrollStep, behavior: reduceMotion ? 'auto' : 'smooth' });
            };

            prevBtn.addEventListener('click', function () {
                scrollByStep(-1);
            });

            nextBtn.addEventListener('click', function () {
                scrollByStep(1);
            });

            scroller.addEventListener('scroll', updateScrollUI, { passive: true });
            window.addEventListener('resize', updateScrollUI);
            updateScrollUI();

            /* Drag-to-scroll (mouse): diabaikan pada elemen interaktif. */
            let isDragging = false;
            let dragMoved = false;
            let dragStartX = 0;
            let dragStartScroll = 0;

            scroller.addEventListener('pointerdown', function (event) {
                if (event.button !== 0 || event.target.closest('button, a')) {
                    return;
                }

                isDragging = true;
                dragMoved = false;
                dragStartX = event.clientX;
                dragStartScroll = scroller.scrollLeft;
            });

            scroller.addEventListener('pointermove', function (event) {
                if (!isDragging) {
                    return;
                }

                const delta = event.clientX - dragStartX;
                if (Math.abs(delta) > 6) {
                    dragMoved = true;
                    scroller.classList.remove('cursor-grab');
                    scroller.classList.add('cursor-grabbing');
                }

                if (dragMoved) {
                    scroller.scrollLeft = dragStartScroll - delta;
                }
            });

            const endDrag = function () {
                if (!isDragging) {
                    return;
                }

                isDragging = false;
                scroller.classList.add('cursor-grab');
                scroller.classList.remove('cursor-grabbing');
            };

            scroller.addEventListener('pointerup', endDrag);
            scroller.addEventListener('pointercancel', endDrag);
            scroller.addEventListener('pointerleave', endDrag);

            scroller.addEventListener('click', function (event) {
                if (dragMoved) {
                    event.preventDefault();
                    event.stopPropagation();
                    dragMoved = false;
                }
            }, true);
        }

        /* ---------- Modal materi tahap ---------- */
        const modalRoot = document.getElementById('stage-modal-root');
        const modal = document.getElementById('stage-modal');
        const kicker = document.getElementById('stage-modal-kicker');
        const modalDot = document.getElementById('stage-modal-dot');
        const modalType = document.getElementById('stage-modal-type');
        const title = document.getElementById('stage-modal-title');
        const description = document.getElementById('stage-modal-description');
        const modalImage = document.getElementById('stage-modal-image');
        const topicsLabel = document.getElementById('stage-modal-topics-label');
        const topicsList = document.getElementById('stage-modal-topics');
        const closeButton = modal.querySelector('[data-stage-close]');
        const backdrop = modalRoot.querySelector('[data-stage-backdrop]');

        if (!modalRoot || !modal) {
            return;
        }

        const accentBars = ['border-t-blue-500', 'border-t-teal-500', 'border-t-green-500', 'border-t-amber-500', 'border-t-orange-500', 'border-t-purple-500'];
        const accentDots = ['bg-blue-500', 'bg-teal-500', 'bg-green-500', 'bg-amber-500', 'bg-orange-500', 'bg-purple-500'];

        let lastTrigger = null;
        let hideTimer = null;

        const focusableSelectors = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';

        const getFocusableElements = function () {
            return Array.prototype.slice.call(modal.querySelectorAll(focusableSelectors)).filter(function (el) {
                return !el.hasAttribute('disabled');
            });
        };

        const applyEntrance = function () {
            backdrop.classList.remove('opacity-0');
            modal.classList.remove('opacity-0', 'scale-95', 'translate-y-2', 'sm:translate-y-0');
        };

        const resetEntrance = function () {
            backdrop.classList.add('opacity-0');
            modal.classList.add('opacity-0', 'scale-95', 'translate-y-2', 'sm:translate-y-0');
        };

        const setAccent = function (barClass) {
            modal.classList.remove.apply(modal.classList, accentBars);
            if (accentBars.indexOf(barClass) !== -1) {
                modal.classList.add(barClass);
            } else {
                modal.classList.add('border-t-blue-500');
            }

            const dotClass = barClass ? barClass.replace('border-t-', 'bg-') : 'bg-blue-500';
            modalDot.classList.remove.apply(modalDot.classList, accentDots);
            modalDot.classList.add(accentDots.indexOf(dotClass) !== -1 ? dotClass : 'bg-blue-500');
        };

        const openModal = function (trigger) {
            const stageNumber = trigger.getAttribute('data-stage-number') || '';
            const stageTitle = trigger.getAttribute('data-stage-title') || '';
            const stageDescription = trigger.getAttribute('data-stage-description') || '';
            const stageTypeLabel = trigger.getAttribute('data-stage-type-label') || '';
            const stageImage = '';

            let topics = [];
            try {
                topics = JSON.parse(trigger.getAttribute('data-stage-topics') || '[]');
            } catch (error) {
                topics = [];
            }

            if (!Array.isArray(topics)) {
                topics = [];
            }

            if (hideTimer) {
                clearTimeout(hideTimer);
                hideTimer = null;
            }

            lastTrigger = trigger;

            setAccent(trigger.getAttribute('data-stage-bar'));
            kicker.textContent = 'Tahap ' + stageNumber;
            modalType.textContent = stageTypeLabel;
            modalType.classList.toggle('hidden', !stageTypeLabel);
            title.textContent = stageTitle;
            topicsLabel.textContent = stageTypeLabel ? 'Cakupan kegiatan' : 'Yang dipelajari';

            modalImage.classList.add('hidden');
            modalImage.removeAttribute('src');
            modalImage.alt = '';

            if (stageDescription) {
                description.textContent = stageDescription;
                description.classList.remove('hidden');
            } else {
                description.textContent = '';
                description.classList.add('hidden');
            }

            topicsList.innerHTML = '';
            if (topics.length === 0) {
                const emptyItem = document.createElement('li');
                emptyItem.textContent = stageTypeLabel ? 'Rincian kegiatan belum ditambahkan.' : 'Rincian topik belum ditambahkan.';
                topicsList.appendChild(emptyItem);
            } else {
                topics.forEach(function (topic) {
                    const item = document.createElement('li');
                    item.textContent = String(topic);
                    topicsList.appendChild(item);
                });
            }

            modalRoot.classList.remove('hidden');
            modalRoot.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');

            if (reduceMotion) {
                applyEntrance();
            } else {
                window.requestAnimationFrame(function () {
                    window.requestAnimationFrame(applyEntrance);
                });
            }

            (closeButton || modal).focus();
        };

        const closeModal = function () {
            const hide = function () {
                modalRoot.classList.add('hidden');
                modalRoot.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
                hideTimer = null;
            };

            resetEntrance();

            if (reduceMotion) {
                hide();
            } else {
                hideTimer = setTimeout(hide, 150);
            }

            if (lastTrigger && document.contains(lastTrigger)) {
                lastTrigger.focus();
            }
            lastTrigger = null;
        };

        const isModalOpen = function () {
            return !modalRoot.classList.contains('hidden');
        };

        // Klik di area mana pun pada overlay menutup modal, sementara klik di
        // dalam isi modal tidak boleh merambat ke overlay. Listener dilepas
        // dari elemen <dialog>, bukan dari elemen backdrop, karena wrapper
        // min-h-full sudah menutupi seluruh area overlay.
        modal.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        modalRoot.addEventListener('click', closeModal);

        document.querySelectorAll('[data-stage-open]').forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                openModal(trigger);
            });
        });

        if (closeButton) {
            closeButton.addEventListener('click', closeModal);
        }

        document.addEventListener('keydown', function (event) {
            if (!isModalOpen()) {
                return;
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                closeModal();
                return;
            }

            if (event.key === 'Tab') {
                const focusable = getFocusableElements();
                if (focusable.length === 0) {
                    event.preventDefault();
                    modal.focus();
                    return;
                }

                const first = focusable[0];
                const last = focusable[focusable.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        });
    })();
</script>
@endsection