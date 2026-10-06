@php
    use App\Models\ProgramKeahlian;
    use App\Support\Site;

    // Dipasang oleh AppServiceProvider lewat view composer supaya navbar dan
    // footer memakai satu query yang sama per halaman.
    $programs = $programs ?? [];

    // "activeOn" mendaftarkan nama route lain yang harus tetap menyalakan
    // menu terkait, misalnya halaman detail berita atau sarana prasarana.
    $nav = [
        ['label' => 'Beranda', 'route' => 'home'],
        [
            'label' => 'Profil Sekolah',
            'children' => [
                ['label' => 'Sejarah', 'route' => 'sejarah'],
                ['label' => 'Visi & Misi', 'route' => 'visi-misi'],
                ['label' => 'Struktur Organisasi', 'route' => 'struktur-organisasi'],
                ['label' => 'Sarana Prasarana', 'route' => 'sarana-prasarana.index', 'activeOn' => ['sarana-prasarana.show']],
            ],
        ],
        [
            'label' => 'Informasi',
            'children' => [
                ['label' => 'Berita & Informasi', 'route' => 'berita.index', 'activeOn' => ['berita.show']],
                ['label' => 'Prestasi', 'route' => 'prestasi'],
                ['label' => 'Download', 'route' => 'download'],
                ['label' => 'PPDB', 'route' => 'ppdb'],
            ],
        ],
[
            'label' => 'Program Keahlian',
            'activeOn' => ['program-keahlian.index', 'program-keahlian.show'],
            'children' => $programs
                ->map(fn ($p) => $p->edurouteProgram?->is_active
                    ? [
                        'label' => $p->title,
                        'route' => 'eduroute.dashboard',
                        'params' => [$p->edurouteProgram->slug],
                        'activeOn' => ['program-keahlian.show:'.$p->slug],
                    ]
                    : [
                        'label' => $p->title,
                        'route' => 'program-keahlian.show',
                        'params' => [$p->slug],
                        'activeOn' => ['program-keahlian.show:'.$p->slug],
                    ])
                ->all(),
        ],
        [
            'label' => 'Kegiatan Siswa',
            'children' => [
                ['label' => 'Ekstrakurikuler', 'route' => 'ekstrakurikuler'],
                ['label' => 'Organisasi Siswa', 'route' => 'organisasi-siswa'],
            ],
        ],
        [
            'label' => 'Layanan',
            'children' => [
                ['label' => 'BLUD', 'route' => 'blud'],
                ['label' => 'PKL', 'route' => 'pkl'],
            ],
        ],
    ];

    $current = request()->route()?->getName();

    // Kunci kedua untuk route berparameter, misalnya "program-keahalian.show:tkj".
    // Tanpa ini halaman detail program keahlian tidak akan menyalakan menunya.
    $program = request()->route()?->parameter('programKeahlian');

    $slug = match (true) {
        $program instanceof ProgramKeahlian => $program->slug,
        is_string($program) => $program,
        default => null,
    };

    $scoped = ($slug === null || $current === null) ? null : $current.':'.$slug;

    $matches = static function (?array $item) use ($current, $scoped): bool {
        foreach ($item['activeOn'] ?? [] as $alias) {
            if ($alias === $current || ($scoped !== null && $alias === $scoped)) {
                return true;
            }
        }

        return false;
    };

    // Item yang punya alias berslug (mengandung ":") hanya boleh menyala
    // lewat $matches. Tanpa penjaga ini perbandingan route mentah akan
    // menyalakan seluruh item sekaligus pada halaman detail yang sama.
    $isItemActive = static function (?array $item) use ($current, $matches): bool {
        if ($item === null || $current === null) {
            return false;
        }

        if ($matches($item)) {
            return true;
        }

        $scopeSensitive = collect($item['activeOn'] ?? [])
            ->contains(fn (string $alias) => str_contains($alias, ':'));

        if (isset($item['route'])) {
            return ! $scopeSensitive && $current === $item['route'];
        }

        return false;
    };

    // Pencocokan sengaja memakai daftar nama route eksplisit, bukan cocok
    // awalan, agar "berita.index" tidak ikut menyala di route tak terkait.
    $isRouteActive = function (array $item) use ($current, $isItemActive): bool {
        if ($current === null) {
            return false;
        }

        if ($isItemActive($item)) {
            return true;
        }

        foreach ($item['children'] ?? [] as $child) {
            if ($isItemActive($child)) {
                return true;
            }
        }

        return false;
    };
@endphp

<nav class="sticky top-0 left-0 z-80 w-full border-b border-slate-200/80 bg-white/85 backdrop-blur-xl supports-[backdrop-filter]:bg-white/75"
     aria-label="Navigasi utama"
     x-data="{ mobileOpen: false, scrolled: false }"
     @scroll.window.throttle.150ms="scrolled = window.scrollY > 8"
     x-effect="document.body.classList.toggle('overflow-hidden', mobileOpen)"
     :class="scrolled ? 'shadow-[0_1px_2px_rgba(15,23,42,0.06),0_8px_24px_-12px_rgba(15,23,42,0.15)]' : ''">
    <div class="container-page">
        <div class="flex h-16 items-center justify-between gap-4 lg:h-20">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3" aria-label="{{ Site::name() }} — Beranda">
                <img src="{{ Site::logo() }}" alt="" width="40" height="40"
                     class="h-10 w-10 object-contain" style="object-fit: contain">
                <span class="flex flex-col leading-none">
                    <span class="font-display text-base font-bold tracking-tight text-slate-900 sm:text-lg">{{ Site::shortName() }}</span>
                    <span class="mt-0.5 hidden text-[11px] font-medium tracking-wide text-slate-500 sm:block">{{ Site::name() }}</span>
                </span>
            </a>

            <div class="hidden items-center gap-0.5 lg:flex">
                @foreach ($nav as $item)
                    @continue(empty($item['route']) && empty($item['children']))

                    @if (isset($item['route']))
                        <a href="{{ route($item['route']) }}"
                           @class([
                               'relative rounded-lg px-3 py-2 text-sm transition-colors',
                               'bg-blue-50 font-semibold text-brand-navy' => $isRouteActive($item),
                               'text-slate-600 hover:bg-slate-100 hover:text-brand-navy' => ! $isRouteActive($item),
                           ])
                           @if ($isRouteActive($item)) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    @else
                        @php $isGroupActive = $isRouteActive($item); @endphp
                        <div class="relative" x-data="navDropdown()" @mouseenter="show()" @mouseleave="hide()">
                            <button type="button" @click="toggle()"
                                    @class([
                                        'inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm transition-colors focus:outline-none',
                                        'bg-blue-50 font-semibold text-brand-navy' => $isGroupActive,
                                        'text-slate-600 hover:bg-slate-100 hover:text-brand-navy' => ! $isGroupActive,
                                    ])
                                    :class="open && 'bg-slate-100'"
                                    aria-haspopup="true" :aria-expanded="open">
                                <span>{{ $item['label'] }}</span>
                                <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="open && 'rotate-180'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div @mouseenter="show()" @mouseleave="hide()"
                                 class="absolute right-0 top-full z-20 mt-2 w-64 origin-top-right rounded-xl border border-slate-200 bg-white p-1.5 shadow-panel transition-all duration-200"
                                 :class="open ? 'visible translate-y-0 opacity-100' : 'invisible -translate-y-1 opacity-0'">
                                <ul class="space-y-0.5">
                                    @foreach ($item['children'] as $child)
                                        <li>
                                            <a href="{{ route($child['route'], $child['params'] ?? []) }}"
                                               @class([
                                                   'flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-sm transition-colors',
                                                   'bg-blue-50 font-semibold text-brand-navy' => $isRouteActive($child),
                                                   'text-slate-600 hover:bg-slate-50 hover:text-brand-navy' => ! $isRouteActive($child),
                                               ])
                                               @if ($isRouteActive($child)) aria-current="page" @endif>{{ $child['label'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="flex items-center gap-2 lg:hidden">
                <button type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100 hover:text-brand-navy"
                        :aria-label="mobileOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'" :aria-expanded="mobileOpen" @click="mobileOpen = !mobileOpen">
                    <svg class="h-6 w-6" x-show="!mobileOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" x-show="mobileOpen" x-cloak fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="overflow-hidden border-t border-slate-100 bg-white transition-[max-height,opacity] duration-300 ease-out lg:hidden"
         :class="mobileOpen ? 'max-h-[80vh] overflow-y-auto opacity-100' : 'max-h-0 opacity-0'"
         :aria-hidden="!mobileOpen"
         x-bind:inert.attr="! mobileOpen">
        <div class="container-page space-y-1.5 py-3">
            @foreach ($nav as $item)
                @continue(empty($item['route']) && empty($item['children']))

                @if (isset($item['route']))
                    <a href="{{ route($item['route']) }}" @click="mobileOpen = false"
                       @class([
                           'flex min-h-11 items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                           'bg-blue-50 font-semibold text-brand-navy' => $isRouteActive($item),
                           'text-slate-700 hover:bg-slate-100' => ! $isRouteActive($item),
                       ])
                       @if ($isRouteActive($item)) aria-current="page" @endif>{{ $item['label'] }}</a>
                @else
                    @php $isGroupActive = $isRouteActive($item); @endphp
                    <details class="group" @if ($isGroupActive) open @endif>
                        <summary @class([
                            'flex min-h-11 cursor-pointer items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                            'bg-blue-50 font-semibold text-brand-navy' => $isGroupActive,
                            'text-slate-700 hover:bg-slate-100' => ! $isGroupActive,
                        ])>
                            <span>{{ $item['label'] }}</span>
                            <svg class="h-4 w-4 text-slate-400 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="mt-1 space-y-1 border-l-2 border-slate-100 pl-3">
                            @foreach ($item['children'] as $child)
                                <a href="{{ route($child['route'], $child['params'] ?? []) }}"
                                   @click="mobileOpen = false"
                                   @class([
                                       'flex min-h-11 items-center rounded-lg px-3 py-2 text-sm transition-colors',
                                       'bg-blue-50 font-semibold text-brand-navy' => $isRouteActive($child),
                                       'text-slate-600 hover:bg-slate-100' => ! $isRouteActive($child),
                                   ])
                                   @if ($isRouteActive($child)) aria-current="page" @endif>{{ $child['label'] }}</a>
                            @endforeach
                        </div>
                    </details>
                @endif
            @endforeach
        </div>
    </div>
</nav>
