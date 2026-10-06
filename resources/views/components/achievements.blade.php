@props(['items' => null])

@php
    $items = $items ?? \App\Models\Achievement::published()->ordered()->get();
    $perView = 3;
@endphp

@if ($items->isNotEmpty())
    <section class="section">
        <div class="container-page">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between" data-reveal="up">
                <div>
                    <p class="eyebrow">Prestasi Siswa</p>
                    <h2 class="mt-3 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Pencapaian {{ \App\Support\Site::shortName() }}
                    </h2>
                </div>
                <x-button :href="route('prestasi')" variant="outline">Lihat Semua</x-button>
            </div>

            <div class="mt-10 hidden lg:block" x-data="carousel({ count: {{ $items->count() }}, perView: {{ $perView }} })" data-reveal="up">
                <div class="relative">
                    <button type="button" @click="prev()" :disabled="index === 0"
                            class="absolute -left-5 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-card transition-all hover:border-brand-navy hover:text-brand-navy disabled:cursor-not-allowed disabled:opacity-40"
                            aria-label="Prestasi sebelumnya">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button type="button" @click="next()" :disabled="index === maxIndex"
                            class="absolute -right-5 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-card transition-all hover:border-brand-navy hover:text-brand-navy disabled:cursor-not-allowed disabled:opacity-40"
                            aria-label="Prestasi berikutnya">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-700 ease-in-out" :style="`transform: translateX(${offset}%)`">
                            @foreach ($items as $item)
                                <div class="w-1/3 shrink-0 px-3">
                                    <x-prestasi-card :item="$item" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center gap-2">
                    @for ($i = 0; $i <= $items->count() - $perView; $i++)
                        <button type="button" @click="goTo({{ $i }})" :class="index === {{ $i }} ? 'w-8 bg-brand-navy' : 'w-2 bg-slate-300 hover:bg-slate-400'"
                                class="h-2 rounded-full transition-all duration-300" aria-label="Slide {{ $i + 1 }}"></button>
                    @endfor
                </div>
            </div>

            @if ($items->count() > 1)
                <p class="mb-3 mt-8 flex items-center justify-end gap-1.5 text-xs font-medium text-slate-500 sm:hidden">
                    Geser untuk melihat prestasi
                    <span aria-hidden="true">&rarr;</span>
                </p>
            @endif

            <div @class([
                '-mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto overscroll-x-contain px-4 pb-3 sm:mx-0 sm:mt-10 sm:grid sm:grid-cols-2 sm:gap-5 sm:overflow-visible sm:px-0 sm:pb-0 lg:hidden',
                'mt-3' => $items->count() > 1,
                'mt-8' => $items->count() <= 1,
            ]) role="list" aria-label="Daftar prestasi">
                @foreach ($items->take(4) as $item)
                    <div class="min-w-[84%] snap-center sm:min-w-0" role="listitem">
                        <x-prestasi-card :item="$item" data-reveal="up" data-reveal-delay="{{ $loop->index * 100 }}" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
