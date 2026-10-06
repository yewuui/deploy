<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo :title="$seoTitle ?? null" :description="$seoDescription ?? null" />

    <link rel="icon" type="image/x-icon" href="{{ asset('images/eduroute/logo/logo SMK.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.documentElement.classList.add('reveal-ready');

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        window.setTimeout(function () {
            if (! document.documentElement.dataset.revealInitialized) {
                document.documentElement.classList.remove('reveal-ready');
            }
        }, 4000);
    </script>

    <style>
        ::-webkit-scrollbar { height: 8px; width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Token gerak: hanya transform, 200ms ease-out. */
        #sidebar {
            transition-property: transform, opacity, margin-left;
            transition-duration: 200ms;
            transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
        }

        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                top: 4.5rem;
                bottom: 0;
                left: 0;
                height: auto !important;
                margin-left: 0;
                transform: translateX(-100%);
                opacity: 0;
                pointer-events: none;
            }

            #sidebar.is-sidebar-open {
                transform: translateX(0);
                opacity: 1;
                pointer-events: auto;
            }
        }

        @media (min-width: 1024px) {
            #sidebar.is-sidebar-collapsed {
                margin-left: -18rem;
                opacity: 0;
                pointer-events: none;
            }
        }

        /* Token permukaan hero: tint royal blue -> school green, sangat lembut. */
        :root {
            --hero-from: #F2F6FE;
            --hero-to: #F0F8F2;
        }

        .dark {
            --hero-from: #101B33;
            --hero-to: #0E2018;
        }

        .hero-surface {
            background-image: linear-gradient(135deg, var(--hero-from) 0%, var(--hero-to) 100%);
        }

        /* Hover lift: transform saja, agar tidak melanggar prefers-reduced-motion.
           Kelas ini menyediakan transisi warna + transform sekaligus, sehingga
           elemen pemakainya tidak perlu menambahkan utility transition lagi. */
        .hover-lift {
            transition-property: color, background-color, border-color, transform;
            transition-duration: 200ms;
            transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        @media (prefers-reduced-motion: reduce) {
            #sidebar {
                transition: none;
            }

            .hover-lift,
            .hover-lift:hover {
                transition: none;
                transform: none;
            }

            *,
            *::before,
            *::after {
                transition-duration: 1ms !important;
                animation-duration: 1ms !important;
            }
        }
    </style>
</head>

@php
    $activeMenu = $activeMenu ?? '';
    $sidebarGrade = $activeGrade ?? null;
    $layoutProgram = $program ?? null;
@endphp

<body class="bg-surface dark:bg-night-canvas text-ink dark:text-night-ink antialiased min-h-screen flex flex-col transition-colors duration-200">

    <nav class="bg-paper dark:bg-night-surface border-b border-hairline dark:border-night-hairline px-3 sm:px-8 py-3.5 flex justify-between items-center gap-2 sm:gap-3 sticky top-0 z-50 transition-colors duration-200">
        <div class="flex min-w-0 items-center gap-2 sm:gap-3">
            <button id="sidebar-toggle" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Buka navigasi jurusan" class="h-11 w-11 shrink-0 rounded-item bg-surface dark:bg-night-raised text-muted hover:text-royal-strong dark:text-night-muted dark:hover:text-night-royal border border-hairline dark:border-night-hairline transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-surface flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

            <img id="program-identity-logo" src="{{ $layoutProgram?->logo_url ?? asset('images/eduroute/logo/logo SMK.png') }}" alt="Logo {{ $layoutProgram->short_name ?? 'SMKN 1 Bantul' }}" class="h-9 w-auto shrink-0 object-contain sm:h-10">
            <div class="min-w-0">
                <p class="hidden text-[11px] font-medium leading-tight text-muted dark:text-night-muted sm:block">SMKN 1 Bantul</p>
                <h1 id="program-identity-name" class="text-small font-semibold text-ink dark:text-night-ink leading-tight truncate">
                    <span class="sm:hidden">{{ $layoutProgram->short_name ?? 'RPL' }}</span>
                    <span class="hidden sm:inline">{{ $layoutProgram->name ?? 'Rekayasa Perangkat Lunak' }}</span>
                </h1>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-1 sm:gap-2">
            <x-eduroute-program-switcher :program="$layoutProgram" :programs="$eduroutePrograms ?? null" />

            <button id="theme-toggle" type="button" aria-label="Ganti tema terang atau gelap" class="h-11 w-11 rounded-item text-muted hover:text-ink hover:bg-surface dark:text-night-muted dark:hover:text-night-ink dark:hover:bg-night-raised transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-surface flex items-center justify-center">
                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0012 21.75a9.753 9.753 0 009.752-6.748z"/></svg>
            </button>
        </div>
    </nav>

    <div class="flex flex-1 min-h-[calc(100vh-4.5rem)]">

        <aside id="sidebar" aria-label="Navigasi program keahlian" class="w-[min(18rem,calc(100vw-3rem))] shrink-0 bg-paper dark:bg-night-surface border-r border-hairline dark:border-night-hairline p-4 flex flex-col justify-between sticky top-[4.5rem] h-[calc(100vh-4.5rem)] overflow-y-auto z-40 lg:w-72">
            <div>
                <p class="text-micro font-semibold uppercase tracking-wider text-muted dark:text-night-muted mb-2 px-3">Navigasi jurusan</p>
                <nav class="flex flex-col gap-1 mb-6" aria-label="Navigasi utama">
                    <a href="{{ route('eduroute.dashboard', $layoutProgram) }}" @if($activeMenu === 'dashboard') aria-current="page" @endif class="relative flex items-center gap-3 w-full px-3 py-3 rounded-item text-small transition-colors duration-200 {{ $activeMenu === 'dashboard' ? 'bg-royal-soft dark:bg-night-royal-soft text-royal-strong dark:text-night-royal font-semibold' : 'text-muted dark:text-night-muted hover:bg-surface dark:hover:bg-night-raised hover:text-ink dark:hover:text-night-ink font-medium' }}">
                        @if($activeMenu === 'dashboard')
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-r-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                        @endif
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"></path><path d="M5 9.5V20a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9.5"></path><path d="M9 21v-6h6v6"></path></svg>
                        Beranda
                    </a>
                </nav>

                <p class="text-micro font-semibold uppercase tracking-wider text-muted dark:text-night-muted mb-2 px-3">Kurikulum</p>
                <nav class="flex flex-col gap-1 mb-6" aria-label="Kurikulum per kelas">
                    @foreach ([10, 11, 12] as $grade)
                        <a href="{{ route('eduroute.roadmap', ['program' => $layoutProgram, 'grade' => $grade]) }}"
                           @if($sidebarGrade == $grade) aria-current="true" @endif
                           class="relative flex items-center gap-3 w-full px-3 py-3 rounded-item text-small transition-colors duration-200 {{ $sidebarGrade == $grade ? 'bg-royal-soft dark:bg-night-royal-soft text-royal-strong dark:text-night-royal font-semibold' : 'text-muted dark:text-night-muted hover:bg-surface dark:hover:bg-night-raised hover:text-ink dark:hover:text-night-ink font-medium' }}">
                            @if($sidebarGrade == $grade)
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-r-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4 2.5 8.5 12 13l9.5-4.5L12 4z"></path><path d="M6 10.5V15c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5"></path><path d="M22 8.5V15"></path></svg>
                            Kelas {{ $grade }}
                        </a>
                    @endforeach
                </nav>

                <hr class="border-hairline dark:border-night-hairline mb-6 transition-colors duration-200">

                <p class="text-micro font-semibold uppercase tracking-wider text-muted dark:text-night-muted mb-2 px-3">Informasi</p>
                <nav class="flex flex-col gap-1" aria-label="Informasi jurusan">
                    <a href="{{ route('eduroute.prospek', $layoutProgram) }}" @if($activeMenu === 'prospek') aria-current="page" @endif class="relative flex items-center gap-3 px-3 py-3 rounded-item text-small transition-colors duration-200 {{ $activeMenu === 'prospek' ? 'bg-royal-soft dark:bg-night-royal-soft text-royal-strong dark:text-night-royal font-semibold' : 'text-muted dark:text-night-muted hover:bg-surface dark:hover:bg-night-raised hover:text-ink dark:hover:text-night-ink font-medium' }}">
                        @if($activeMenu === 'prospek')
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-r-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                        @endif
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Prospek Karier
                    </a>

                    <a href="{{ route('eduroute.prestasi', $layoutProgram) }}" @if($activeMenu === 'prestasi') aria-current="page" @endif class="relative flex items-center gap-3 px-3 py-3 rounded-item text-small transition-colors duration-200 {{ $activeMenu === 'prestasi' ? 'bg-royal-soft dark:bg-night-royal-soft text-royal-strong dark:text-night-royal font-semibold' : 'text-muted dark:text-night-muted hover:bg-surface dark:hover:bg-night-raised hover:text-ink dark:hover:text-night-ink font-medium' }}">
                        @if($activeMenu === 'prestasi')
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-r-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                        @endif
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        Prestasi
                    </a>

                    <a href="{{ route('eduroute.media', $layoutProgram) }}" @if($activeMenu === 'media') aria-current="page" @endif class="relative flex items-center gap-3 px-3 py-3 rounded-item text-small transition-colors duration-200 {{ $activeMenu === 'media' ? 'bg-royal-soft dark:bg-night-royal-soft text-royal-strong dark:text-night-royal font-semibold' : 'text-muted dark:text-night-muted hover:bg-surface dark:hover:bg-night-raised hover:text-ink dark:hover:text-night-ink font-medium' }}">
                        @if($activeMenu === 'media')
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-r-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                        @endif
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Media Sosial
                    </a>
                </nav>
            </div>

            <div class="border-t border-hairline dark:border-night-hairline mt-6 pt-4">
                <a href="{{ route('home') }}"
                   class="mt-2 flex min-h-[44px] w-full items-center justify-center gap-2 rounded-item border border-hairline dark:border-night-hairline bg-paper dark:bg-night-surface px-4 py-2.5 text-[13px] font-semibold text-ink dark:text-night-ink transition-colors duration-200 hover:border-royal hover:text-royal-strong dark:hover:border-night-royal dark:hover:text-night-royal focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-surface">
                    Kembali ke Website Sekolah
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
        </aside>

        <button id="sidebar-backdrop" type="button" tabindex="-1" aria-hidden="true" aria-label="Tutup sidebar" class="hidden fixed inset-0 bg-night-canvas/60 z-30 lg:hidden"></button>

        <main class="min-w-0 w-full flex-1 bg-surface p-4 transition-colors duration-200 dark:bg-night-canvas sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <footer class="border-t border-hairline bg-paper/90 dark:border-night-hairline dark:bg-night-surface/90">
        <div class="mx-auto max-w-5xl px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center gap-2 text-center">
                
                <p class="text-[12px] text-muted dark:text-night-muted">
                    Copyright©{{ date('Y') }} 404 Not Found • SMK Negeri 1 Bantul <br> Alright Reserved
                </p>
            </div>
        </div>
    </footer>

    <script id="eduroute-shell-scripts">
        const themeToggleBtn = document.getElementById('theme-toggle');

        const syncThemeButtonState = function() {
            const isDark = document.documentElement.classList.contains('dark');
            themeToggleBtn.setAttribute('aria-pressed', String(isDark));
        };

        syncThemeButtonState();

        themeToggleBtn.addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');

            document.documentElement.classList.toggle('dark', !isDark);
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
            syncThemeButtonState();
        });

        const sidebarToggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const desktopSidebar = window.matchMedia('(min-width: 1024px)');

        const setSidebarExpandedState = function(isExpanded) {
            sidebarToggleBtn.setAttribute('aria-expanded', String(isExpanded));
            sidebarToggleBtn.setAttribute('aria-label', isExpanded ? 'Tutup navigasi jurusan' : 'Buka navigasi jurusan');
            sidebar.setAttribute('aria-hidden', String(!isExpanded));
            sidebar.inert = !isExpanded;
        };

        const closeMobileSidebar = function() {
            sidebar.classList.remove('is-sidebar-open');
            sidebarBackdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            setSidebarExpandedState(false);
        };

        const syncSidebarWithViewport = function() {
            closeMobileSidebar();

            if (desktopSidebar.matches) {
                setSidebarExpandedState(!sidebar.classList.contains('is-sidebar-collapsed'));
            } else {
                sidebar.classList.remove('is-sidebar-collapsed');
            }
        };

        syncSidebarWithViewport();

        sidebarToggleBtn.addEventListener('click', function() {
            if (desktopSidebar.matches) {
                const isCollapsed = sidebar.classList.toggle('is-sidebar-collapsed');
                setSidebarExpandedState(!isCollapsed);
                return;
            }

            const isOpen = sidebar.classList.toggle('is-sidebar-open');
            sidebarBackdrop.classList.toggle('hidden', !isOpen);
            document.body.classList.toggle('overflow-hidden', isOpen);
            setSidebarExpandedState(isOpen);
        });

        sidebarBackdrop.addEventListener('click', closeMobileSidebar);

        desktopSidebar.addEventListener('change', syncSidebarWithViewport);

        sidebar.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                if (!desktopSidebar.matches) {
                    closeMobileSidebar();
                }
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !desktopSidebar.matches && sidebar.classList.contains('is-sidebar-open')) {
                closeMobileSidebar();
                sidebarToggleBtn.focus();
            }
        });

/* ---------- Pemilih jurusan di header ---------- */
        const programSwitcher = document.querySelector('[data-program-switcher]');
        const switcherTrigger = programSwitcher ? programSwitcher.querySelector('[data-program-switcher-trigger]') : null;
        const switcherPanel = programSwitcher ? programSwitcher.querySelector('[data-program-switcher-panel]') : null;
        const switcherChevron = programSwitcher ? programSwitcher.querySelector('[data-program-switcher-chevron]') : null;
        const switcherOptions = programSwitcher ? Array.prototype.slice.call(programSwitcher.querySelectorAll('[data-program-option]')) : [];

        const isProgramSwitcherOpen = function() {
            return switcherPanel !== null && switcherPanel.classList.contains('visible');
        };

        const openProgramSwitcher = function() {
            switcherPanel.classList.remove('invisible', 'opacity-0', '-translate-y-1');
            switcherPanel.classList.add('visible', 'opacity-100', 'translate-y-0');
            switcherTrigger.setAttribute('aria-expanded', 'true');
            switcherChevron.classList.add('rotate-180');
        };

        const closeProgramSwitcher = function() {
            if (!isProgramSwitcherOpen()) {
                return;
            }

            switcherPanel.classList.add('invisible', 'opacity-0', '-translate-y-1');
            switcherPanel.classList.remove('visible', 'opacity-100', 'translate-y-0');
            switcherTrigger.setAttribute('aria-expanded', 'false');
            switcherChevron.classList.remove('rotate-180');
        };

        const focusSwitcherOption = function(index) {
            if (switcherOptions.length === 0) {
                return;
            }

            switcherOptions[(index + switcherOptions.length) % switcherOptions.length].focus();
        };

        if (programSwitcher) {
            switcherTrigger.addEventListener('click', function() {
                if (isProgramSwitcherOpen()) {
                    closeProgramSwitcher();
                    return;
                }

                openProgramSwitcher();
            });

            switcherTrigger.addEventListener('keydown', function(event) {
                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    openProgramSwitcher();
                    focusSwitcherOption(0);
                }
            });

            switcherOptions.forEach(function(option, index) {
                option.addEventListener('keydown', function(event) {
                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        focusSwitcherOption(index + 1);
                    } else if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        focusSwitcherOption(index - 1);
                    }
                });
            });

            document.addEventListener('click', function(event) {
                if (isProgramSwitcherOpen() && !programSwitcher.contains(event.target)) {
                    closeProgramSwitcher();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && isProgramSwitcherOpen()) {
                    closeProgramSwitcher();
                    switcherTrigger.focus();
                }
            });
        }

        /* ---------- Perpindahan jurusan tanpa muat ulang halaman penuh ----------
             Hanya isi <main> yang ditukar. Shell (tema, sidebar, pemilih
             jurusan) tetap hidup karena script ini tidak pernah dijalankan
             ulang, dan script halaman yang baru ikut dijalankan di dalam
             ruang lingkup fungsi supaya deklarasinya tidak bentrok. ---------- */
        const edurouteNavigation = (function() {
            const mainRegion = document.querySelector('main');
            const identityLogo = document.getElementById('program-identity-logo');
            const identityName = document.getElementById('program-identity-name');
            const fallbackLogo = identityLogo ? identityLogo.getAttribute('src') : '';
            const shellScriptId = 'eduroute-shell-scripts';

            if (!mainRegion) {
                return null;
            }

            let isNavigating = false;

            const findProgramOption = function(slug) {
                return document.querySelector('[data-program-option][data-slug="' + slug + '"]');
            };

            const slugFromUrl = function(url) {
                const match = String(url).match(/\/eduroute\/([^/?#]+)/);

                return match ? match[1] : null;
            };

            const syncIdentity = function(slug) {
                const option = findProgramOption(slug);

                if (!option) {
                    return;
                }

                const name = option.getAttribute('data-name') || '';
                const logo = option.getAttribute('data-logo') || fallbackLogo;

                if (identityName) {
                    identityName.textContent = name;
                }

                if (identityLogo && logo) {
                    identityLogo.setAttribute('src', logo);
                    identityLogo.setAttribute('alt', 'Logo ' + (option.getAttribute('data-short-name') || name));
                }
            };

            const syncActiveOption = function(slug) {
                document.querySelectorAll('[data-program-option]').forEach(function(option) {
                    const isActive = option.getAttribute('data-slug') === slug;

                    option.setAttribute('data-active', isActive ? 'true' : 'false');

                    if (isActive) {
                        option.setAttribute('aria-current', 'page');
                    } else {
                        option.removeAttribute('aria-current');
                    }
                });

                const activeOption = findProgramOption(slug);

                document.querySelectorAll('[data-program-switcher-current]').forEach(function(label) {
                    if (activeOption) {
                        label.textContent = activeOption.getAttribute('data-short-name') || '';
                    }
                });
            };

            const syncSidebarProgramLinks = function(slug) {
                document.querySelectorAll('#sidebar a[href*="/eduroute/"]').forEach(function(link) {
                    const href = link.getAttribute('href');

                    if (!href) {
                        return;
                    }

                    try {
                        const url = new URL(href, window.location.origin);
                        url.pathname = url.pathname.replace(/^\/eduroute\/[^/]+/, '/eduroute/' + slug);
                        link.setAttribute('href', url.href);
                    } catch (error) {
                        // Abaikan bila URL tidak valid; fallback ke link awal.
                    }
                });
            };

            const runPageScripts = function(loadedDocument) {
                loadedDocument.querySelectorAll('body script').forEach(function(script) {
                    if (script.src || script.type || script.id === shellScriptId) {
                        return;
                    }

                    new Function(script.textContent)();
                });
            };

            const navigateTo = function(targetUrl, shouldPushState) {
                if (isNavigating) {
                    return;
                }

                isNavigating = true;

                // Posisi bacaan disimpan supaya perpindahan jurusan tidak
                // membuat pengguna melompat kembali ke paling atas halaman.
                const restoreScrollTop = window.scrollY;

                const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                // Animasi halus keluar ketika user memilih jurusan
                if (!prefersReducedMotion) {
                    mainRegion.style.transition = 'opacity 150ms cubic-bezier(0.4, 0, 0.2, 1), transform 150ms cubic-bezier(0.4, 0, 0.2, 1)';
                    mainRegion.style.opacity = '0.35';
                    mainRegion.style.transform = 'translateY(6px)';
                }

                window.fetch(targetUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                }).then(function(response) {
                    if (!response.ok) {
                        throw new Error('Permintaan halaman gagal');
                    }

                    return response.text().then(function(html) {
                        return { html: html, url: response.url || targetUrl };
                    });
                }).then(function(result) {
                    const loadedDocument = new DOMParser().parseFromString(result.html, 'text/html');
                    const nextMain = loadedDocument.querySelector('main');

                    if (!nextMain) {
                        throw new Error('Konten halaman tidak ditemukan');
                    }

                    mainRegion.innerHTML = nextMain.innerHTML;

                    // Kunci scroll body dilepas karena node modal lama sudah
                    // dibuang bersama <main>.
                    document.body.classList.remove('overflow-hidden');
                    document.body.style.overflow = '';

                    if (loadedDocument.title) {
                        document.title = loadedDocument.title;
                    }

                    const nextSlug = slugFromUrl(result.url);
                    syncIdentity(nextSlug);
                    syncActiveOption(nextSlug);
                    syncSidebarProgramLinks(nextSlug);
                    runPageScripts(loadedDocument);

                    window.scrollTo(0, restoreScrollTop);

                    if (shouldPushState) {
                        window.history.pushState({}, '', result.url);
                    }

                    // Mainkan animasi reveal yang seirama dengan web sekolah
                    if (!prefersReducedMotion) {
                        window.requestAnimationFrame(function() {
                            mainRegion.style.opacity = '1';
                            mainRegion.style.transform = 'translateY(0)';

                            if (window.initScrollReveal) {
                                window.initScrollReveal(mainRegion, true);
                            }
                        });
                    } else {
                        mainRegion.style.opacity = '';
                        mainRegion.style.transform = '';
                        if (window.initScrollReveal) {
                            window.initScrollReveal(mainRegion, false);
                        }
                    }

                    isNavigating = false;
                }).catch(function() {
                    // Fallback aman: navigasi biasa bila pertukaran konten gagal.
                    isNavigating = false;
                    mainRegion.style.opacity = '';
                    mainRegion.style.transform = '';

                    if (shouldPushState) {
                        window.location.assign(targetUrl);
                        return;
                    }

                    window.location.replace(targetUrl);
                });
            };

            return { navigateTo: navigateTo };
        })();

        if (programSwitcher) {
            switcherOptions.forEach(function(option) {
                option.addEventListener('click', function(event) {
                    if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                        return;
                    }

                    event.preventDefault();
                    closeProgramSwitcher();

                    if (edurouteNavigation) {
                        edurouteNavigation.navigateTo(option.href, true);
                    }
                });
            });

            window.addEventListener('popstate', function() {
                if (!edurouteNavigation) {
                    return;
                }

                edurouteNavigation.navigateTo(window.location.href, false);
            });
        }
    </script>

    @yield('page-scripts')
</body>
</html>
