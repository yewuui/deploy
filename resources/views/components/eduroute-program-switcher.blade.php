@props([
    'program' => null,
    'programs' => null,
    'pageRoute' => null,
    'pageParameters' => [],
])

@php
    /*
     * Pemilih jurusan EduRoute. Dipakai di header layout EduRoute supaya
     * user bisa berpindah jurusan tanpa kembali ke website sekolah.
     *
     * Target URL dibangun dari route halaman yang sedang aktif, bukan dari
     * beranda. Jadi setelah jurusan berubah, halaman yang sama tetap terbuka
     * (misalnya roadmap kelas 11) memakai routing yang sudah ada.
     */
    $programList = $programs ?? collect();

    $switchableRoutes = [
        'eduroute.dashboard',
        'eduroute.roadmap',
        'eduroute.prospek',
        'eduroute.prestasi',
        'eduroute.media',
    ];

    $currentRoute = $pageRoute ?? request()->route()?->getName();

    if (! in_array($currentRoute, $switchableRoutes, true)) {
        $currentRoute = 'eduroute.dashboard';
    }

    $currentParameters = $pageRoute === null
        ? array_merge(request()->query(), array_diff_key(request()->route()?->parameters() ?? [], ['program' => true]))
        : array_diff_key($pageParameters, ['program' => true]);

    $targetUrl = fn ($target) => route($currentRoute, array_merge(['program' => $target], $currentParameters));

    $isActive = fn ($candidate) => $program !== null && $candidate->slug === $program->slug;
@endphp

<div class="relative shrink-0" data-program-switcher>
    <button
        type="button"
        data-program-switcher-trigger
        aria-haspopup="true"
        aria-expanded="false"
        aria-controls="program-switcher-panel"
        class="inline-flex h-11 items-center gap-1.5 rounded-item border border-hairline bg-surface px-2.5 text-[13px] font-semibold text-ink transition-colors duration-200 hover:border-royal hover:text-royal-strong focus:outline-none focus-visible:ring-2 focus-visible:ring-royal focus-visible:ring-offset-2 dark:border-night-hairline dark:bg-night-raised dark:text-night-ink dark:hover:border-night-royal dark:hover:text-night-royal dark:focus-visible:ring-night-royal dark:focus-visible:ring-offset-night-surface sm:gap-2 sm:px-3"
    >
        <span class="sm:hidden">Jurusan</span>
        <span class="hidden sm:inline">Pilih Jurusan</span>
        <svg class="w-3.5 h-3.5 shrink-0 text-muted transition-transform duration-200 dark:text-night-muted" data-program-switcher-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
        <span class="sr-only">Jurusan {{ $program?->short_name }}</span>
    </button>

    <div
        id="program-switcher-panel"
        data-program-switcher-panel
        class="invisible absolute right-0 top-full z-50 mt-2 w-72 max-w-[calc(100vw-2rem)] origin-top-right rounded-card border border-hairline bg-paper p-1.5 opacity-0 shadow-lift transition-[opacity,transform,visibility] duration-150 ease-out -translate-y-1 dark:border-night-hairline dark:bg-night-surface"
    >
        <p class="px-3 pt-1.5 pb-1.5 text-micro font-semibold uppercase tracking-wider text-muted dark:text-night-muted">Pindah jurusan</p>

        <ul class="max-h-[min(20rem,55vh)] overflow-y-auto overscroll-contain">
            @forelse ($programList as $option)
                @php
                    $displayName = $option->programKeahlian?->title ?? $option->name;
                @endphp
                <li>
                    <a
                        href="{{ $targetUrl($option) }}"
                        data-program-option
                        data-slug="{{ $option->slug }}"
                        data-name="{{ $displayName }}"
                        data-short-name="{{ $option->short_name }}"
                        data-logo="{{ $option->logo_url ?? '' }}"
                        data-active="{{ $isActive($option) ? 'true' : 'false' }}"
                        @if ($isActive($option)) aria-current="page" @endif
                        class="flex min-h-[44px] items-center gap-2.5 rounded-item px-3 py-2 text-small text-ink transition-colors duration-200 hover:bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:text-night-ink dark:hover:bg-night-raised dark:focus-visible:ring-night-royal data-[active=true]:bg-royal-soft data-[active=true]:font-semibold data-[active=true]:text-royal-strong dark:data-[active=true]:bg-night-royal-soft dark:data-[active=true]:text-night-royal"
                    >
                        <span class="inline-flex h-7 w-11 shrink-0 items-center justify-center rounded-md bg-surface text-micro font-bold tabular-nums text-muted dark:bg-night-raised dark:text-night-muted">{{ $option->short_name }}</span>
                        <span class="min-w-0 flex-1 break-words">{{ $displayName }}</span>
                    </a>
                </li>
            @empty
                <li>
                    <p class="px-3 py-2.5 text-micro text-muted dark:text-night-muted">Belum ada jurusan lain yang dapat dipilih.</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>
