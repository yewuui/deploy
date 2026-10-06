@extends('layouts.app')

@php
    $seoTitle = 'BLUD';
    $seoDescription = 'Kenali usulan unit usaha dan layanan produktif BLUD '.\App\Support\Site::name().' yang turut menjadi ruang praktik bagi siswa.';
@endphp

@section('content')
    <x-page-title text="BLUD"
                  description="BLUD (Badan Layanan Umum Daerah) merupakan pola pengelolaan layanan daerah yang memberi keleluasaan operasional untuk mendukung pelayanan. Di SMKN 1 Bantul, BLUD disiapkan untuk mengelola usaha dan layanan produktif sekolah, menjadi ruang praktik bagi siswa, serta memberi manfaat bagi masyarakat. Penerapannya direncanakan mulai 2028.">
        <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-900">
            <span class="h-2 w-2 rounded-full bg-amber-500" aria-hidden="true"></span>
            Tahap pengajuan · rencana mulai 2028
        </span>
    </x-page-title>

    <div class="container-page max-w-6xl py-10 lg:py-14">
        <x-breadcrumbs :breadcrumbs="[['label' => 'Layanan'], ['label' => 'BLUD']]" />

        <section class="mt-8 overflow-hidden rounded-3xl bg-brand-teal text-white shadow-panel" aria-labelledby="blud-proposal-heading" data-reveal="up">
            <div class="grid gap-6 p-5 sm:p-8 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-200">Rencana pengembangan layanan</p>
                    <h2 id="blud-proposal-heading" class="mt-3 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        {{ $units->count() }} unit usaha dan layanan yang diusulkan
                    </h2>
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/80">
                        Daftar ini memuat rancangan unit yang diajukan untuk dikelola melalui BLUD. Seluruhnya masih dalam tahap pengajuan, dengan penerapan direncanakan mulai 2028.
                    </p>
                </div>
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl border border-white/15 bg-white/10 font-display text-3xl font-bold text-cyan-200 md:h-24 md:w-24" aria-hidden="true">
                    2028
                </div>
            </div>
        </section>

        <section class="mt-10" aria-labelledby="blud-units-heading">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="eyebrow">Ragam layanan</p>
                    <h2 id="blud-units-heading" class="mt-2 font-display text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Rencana unit BLUD
                    </h2>
                </div>
                <p class="text-sm text-slate-500">Ruang praktik dari berbagai bidang keahlian</p>
            </div>

            <div class="space-y-3" data-reveal-parent>
                @forelse ($units as $unit)
                    @php($unitDetail = $unitDetails[$unit->name] ?? [])
                    <article class="group grid grid-cols-[2.75rem_5rem_minmax(0,1fr)] items-center gap-3 rounded-2xl border border-slate-200 border-l-4 border-l-brand-sky bg-white p-3 shadow-card transition-colors hover:border-brand-sky/50 sm:grid-cols-[3.5rem_8rem_minmax(0,1fr)] sm:gap-5 sm:p-4" data-reveal="up" data-reveal-delay="{{ min(($loop->index % 4) * 60, 180) }}">
                        <span class="font-display text-xl font-bold tabular-nums text-slate-300 sm:text-2xl" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                        <div class="aspect-square w-20 overflow-hidden rounded-xl bg-surface-muted sm:w-32">
                            <img src="{{ $unit->image_url }}" alt="{{ $unit->name }}" loading="lazy" decoding="async" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>

                        <div class="min-w-0 py-1">
                            <h3 class="font-display text-base font-bold leading-snug text-slate-900 sm:text-lg">{{ $unit->name }}</h3>
                            @if (! empty($unitDetail['manager']))
                                <p class="mt-1.5 inline-flex max-w-full rounded-md bg-blue-50 px-2 py-1 text-[11px] font-semibold leading-snug text-brand-navy">
                                    Dikelola oleh {{ $unitDetail['manager'] }}
                                </p>
                            @endif
                            @if (! empty($unitDetail['description']))
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $unitDetail['description'] }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-300 p-6 text-sm text-slate-600">Informasi unit BLUD belum tersedia.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
