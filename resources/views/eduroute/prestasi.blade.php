@extends('layouts.eduroute', ['activeMenu' => 'prestasi'])

@php
    $seoTitle = 'Jejak Prestasi '.$program->short_name;
    $seoDescription = 'Daftar prestasi siswa program keahlian '.$program->name.' SMKN 1 Bantul.';
@endphp

@section('content')
@php
    // Tilen warna chip level. Dipetakan dari nilai level di database;
    // nilai yang tidak dikenal memakai tilen netral. Hanya presentasi.
    $levelTiles = [
        'provinsi' => 'bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal',
        'nasional' => 'bg-leaf-soft text-leaf-strong dark:bg-night-leaf-soft dark:text-night-leaf',
        'internasional' => 'bg-gold-soft text-gold-strong dark:bg-night-gold-soft dark:text-night-gold',
    ];
@endphp

<div class="mx-auto w-full max-w-[1200px]">
    <div class="mb-6 sm:mb-8" data-reveal="up">
        <div class="inline-flex flex-col items-start">
            <h2 class="text-h2 font-semibold text-ink dark:text-night-ink mb-2">Prestasi Jurusan {{ $program->short_name }}</h2>
            <span class="mb-3 block h-0.5 w-full min-w-[4rem] rounded-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
        </div>
        <p class="mb-6 max-w-3xl text-small text-muted dark:text-night-muted">
            Pencapaian dan prestasi siswa {{ $program->name }}. Klik salah satu kartu untuk membaca cerita lengkap di balik prestasi tersebut.
        </p>

        @if($achievements->isNotEmpty())
            <div class="relative w-full max-w-xl">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-muted dark:text-night-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="search" id="searchInput" aria-label="Cari nama atau lomba" class="block w-full rounded-item border border-hairline bg-paper py-3 pl-11 pr-4 text-small text-ink placeholder:text-muted focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent dark:border-night-hairline dark:bg-night-surface dark:text-night-ink dark:placeholder:text-night-muted dark:focus:ring-night-royal" placeholder="Cari nama siswa atau nama lomba...">
            </div>
        @endif
    </div>

    @if($achievements->isEmpty())
        <div class="rounded-card border border-hairline bg-paper p-6 text-center shadow-soft dark:border-night-hairline dark:bg-night-surface sm:p-10" data-reveal="up">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gold-soft text-gold-strong dark:bg-night-gold-soft dark:text-night-gold">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4M5 4h14v5a7 7 0 01-14 0V4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 6H3a2 2 0 000 4h2m14-4h2a2 2 0 010 4h-2"/></svg>
            </div>
            <p class="text-small font-semibold text-ink dark:text-night-ink">Segera Hadir</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 pb-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($achievements as $item)
                @php
                    $levelKey = strtolower(trim((string) $item->level));
                    $levelTile = $levelTiles[$levelKey] ?? 'bg-surface text-muted dark:bg-night-raised dark:text-night-muted';
                    $summary = \Illuminate\Support\Str::limit(strip_tags((string) $item->description), 110);
                @endphp
                <article data-achievement-card="{{ $loop->index }}" tabindex="0" role="button" aria-haspopup="dialog" aria-label="Lihat detail {{ $item->title }}" data-reveal="up" data-reveal-delay="{{ min(($loop->index % 3 + 1) * 100, 300) }}" class="group hover-lift flex h-full min-w-0 cursor-pointer flex-col overflow-hidden rounded-card border border-hairline bg-paper shadow-soft hover:border-royal/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-royal focus-visible:ring-offset-2 dark:border-night-hairline dark:bg-night-surface dark:hover:border-night-royal/40 dark:focus-visible:ring-night-royal dark:focus-visible:ring-offset-night-canvas">
                    @if($item->image_url)
                        <div class="relative aspect-video w-full overflow-hidden bg-surface dark:bg-night-raised">
                            <img src="{{ $item->image_url }}" alt="Dokumentasi {{ $item->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" decoding="async">
                            <span class="pointer-events-none absolute inset-0 flex items-end bg-gradient-to-t from-black/45 via-transparent to-transparent p-3 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <span class="inline-flex items-center gap-1 rounded-item bg-black/55 px-2.5 py-1 text-micro font-semibold text-white">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat detail
                                </span>
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-1 flex-col p-5">
                        @if($item->category || $item->level)
                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                @if($item->category)
                                    <span class="inline-flex items-center rounded-md bg-surface px-2.5 py-1 text-micro font-semibold text-muted dark:bg-night-raised dark:text-night-muted">{{ $item->category }}</span>
                                @endif
                                @if($item->level)
                                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-micro font-semibold {{ $levelTile }}">{{ $item->level }}</span>
                                @endif
                            </div>
                        @endif

                        <h3 class="text-small font-semibold leading-snug text-ink transition-colors group-hover:text-royal-strong dark:text-night-ink dark:group-hover:text-night-royal">
                            {{ $item->title }}
                        </h3>

                        @if($item->name)
                            <p class="mt-1.5 text-small font-medium text-ink/80 dark:text-night-ink/80">
                                {{ $item->name }}
                            </p>
                        @endif

                        <p class="mt-2 flex-1 text-small text-muted dark:text-night-muted">
                            {{ $summary ?: 'Klik untuk membaca cerita lengkap prestasi ini.' }}
                        </p>

                        <div class="mt-4 flex items-center justify-between gap-3 border-t border-hairline pt-3 text-micro font-medium text-muted dark:border-night-hairline dark:text-night-muted">
                            <span class="flex min-w-0 items-center">
                                @if($item->date_label)
                                    <svg class="mr-1.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="truncate">{{ $item->date_label }}</span>
                                @else
                                    <span class="truncate">{{ $program->short_name }}</span>
                                @endif
                            </span>
                            <span class="inline-flex shrink-0 items-center gap-1 font-semibold text-royal-strong transition-transform duration-200 group-hover:translate-x-0.5 dark:text-night-royal" aria-hidden="true">
                                Detail
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </span>
                        </div>
                    </div>
                </article>
            @endforeach

            <div id="noResults" class="hidden col-span-full flex-col items-center justify-center py-16 text-center text-muted dark:text-night-muted">
                <svg class="mb-3 h-10 w-10 opacity-60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-small font-semibold">Tidak ada prestasi yang cocok dengan pencarian Anda.</p>
                <p class="mt-1 text-micro">Coba kata kunci lain, misalnya nama siswa atau nama lomba.</p>
            </div>
        </div>
    @endif
</div>

<div id="achievement-modal" class="hidden fixed inset-0 z-[60]" aria-hidden="true">
    <div id="achievement-backdrop" class="absolute inset-0 bg-night-canvas/60"></div>
    <div class="relative flex min-h-full items-center justify-center p-4">
        <div role="dialog" aria-modal="true" aria-labelledby="achievement-modal-title" class="flex max-h-[88vh] w-full max-w-2xl flex-col overflow-hidden rounded-card border border-hairline bg-paper shadow-lift dark:border-night-hairline dark:bg-night-surface">
            <div class="flex items-start justify-between gap-4 border-b border-hairline p-5 dark:border-night-hairline">
                <div class="min-w-0">
                    <p class="text-micro font-semibold text-muted dark:text-night-muted">Detail Prestasi {{ $program->short_name }}</p>
                    <h3 id="achievement-modal-title" class="mt-0.5 text-body font-semibold leading-snug text-ink dark:text-night-ink"></h3>
                </div>
                <button id="achievement-modal-close" type="button" aria-label="Tutup detail prestasi" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-item text-muted transition-colors duration-200 hover:bg-surface hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:text-night-muted dark:hover:bg-night-raised dark:hover:text-night-ink dark:focus-visible:ring-night-royal">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto">
                <img id="achievement-modal-image" src="" alt="" class="hidden max-h-[300px] w-full object-cover">
                <div class="space-y-5 p-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span id="achievement-modal-category" class="hidden inline-flex items-center rounded-md bg-surface px-2.5 py-1 text-micro font-semibold text-muted dark:bg-night-raised dark:text-night-muted"></span>
                        <span id="achievement-modal-level" class="hidden inline-flex items-center rounded-md bg-gold-soft px-2.5 py-1 text-micro font-semibold text-gold-strong dark:bg-night-gold-soft dark:text-night-gold"></span>
                        <span id="achievement-modal-date" class="hidden inline-flex items-center gap-1.5 text-micro font-medium text-muted dark:text-night-muted">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span id="achievement-modal-date-text"></span>
                        </span>
                    </div>

                    <dl id="achievement-modal-meta" class="hidden divide-y divide-hairline overflow-hidden rounded-item border border-hairline bg-surface dark:divide-night-hairline dark:border-night-hairline dark:bg-night-raised"></dl>

                    <div id="achievement-modal-description" class="space-y-3 text-small leading-relaxed text-muted dark:text-night-muted"></div>

                    <p id="achievement-modal-attribution" class="hidden border-t border-hairline pt-4 text-micro text-muted dark:border-night-hairline dark:text-night-muted"></p>
                </div>
            </div>

            <div class="flex justify-end border-t border-hairline p-3 dark:border-night-hairline">
                <button id="achievement-modal-done" type="button" class="rounded-item bg-royal px-5 py-2 text-small font-semibold text-white transition-colors hover:bg-royal-strong focus:outline-none focus-visible:ring-2 focus-visible:ring-royal focus-visible:ring-offset-2 dark:bg-night-royal dark:hover:bg-night-royal/80">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    // Payload modal diserialisasi sekali di server. Bentuk array datar supaya
    // @json() tidak perlu parse ekspresi PHP yang bertanda kurung bersarang.
    $achievementPayload = $achievements
        ->map(fn ($item) => [
            'title' => $item->title,
            'name' => $item->name,
            'date_label' => $item->date_label,
            'category' => $item->category,
            'level' => $item->level,
            'description' => $item->description,
            'image_url' => $item->image_url,
        ])
        ->values();
@endphp

@section('page-scripts')
<script>
    const ACHIEVEMENTS = @json($achievementPayload);

    const achievementCards = document.querySelectorAll('[data-achievement-card]');
    const achievementModal = document.getElementById('achievement-modal');
    const achievementDialog = achievementModal.querySelector('[role="dialog"]');
    const achievementCloseBtn = document.getElementById('achievement-modal-close');
    const achievementDoneBtn = document.getElementById('achievement-modal-done');
    const modalImage = document.getElementById('achievement-modal-image');
    const modalCategory = document.getElementById('achievement-modal-category');
    const modalLevel = document.getElementById('achievement-modal-level');
    const modalDate = document.getElementById('achievement-modal-date');
    const modalDateText = document.getElementById('achievement-modal-date-text');
    const modalMeta = document.getElementById('achievement-modal-meta');
    const modalDescription = document.getElementById('achievement-modal-description');
    const modalAttribution = document.getElementById('achievement-modal-attribution');
    let lastFocusedCard = null;

    // Pencarian hanya menyaring kartu yang tampil. Data modal sudah dimuat
    // sekali di atas lewat direktif json Blade, jadi tidak ada state yang perlu disinkronkan.
    const searchInput = document.getElementById('searchInput');
    const noResults = document.getElementById('noResults');

    if (searchInput && noResults) {
        searchInput.addEventListener('input', function (event) {
            const searchTerm = event.target.value.trim().toLowerCase();
            let hasVisibleCards = false;

            achievementCards.forEach(function (card) {
                const matches = card.textContent.toLowerCase().includes(searchTerm);
                card.classList.toggle('hidden', !matches);
                if (matches) {
                    hasVisibleCards = true;
                }
            });

            noResults.classList.toggle('hidden', hasVisibleCards);
        });
    }

    // Data teks dari admin selalu ditulis lewat textContent supaya tidak
    // pernah diperlakukan sebagai HTML.
    const setBadge = function (element, value) {
        element.textContent = value || '';
        element.classList.toggle('hidden', !value);
    };

    const renderMetaRows = function (rows) {
        modalMeta.replaceChildren();

        rows.forEach(function (row) {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex flex-wrap items-baseline justify-between gap-2 px-4 py-2.5';

            const label = document.createElement('dt');
            label.className = 'text-micro font-medium uppercase tracking-wide text-muted dark:text-night-muted';
            label.textContent = row.label;

            const value = document.createElement('dd');
            value.className = 'text-small font-semibold text-ink dark:text-night-ink';
            value.textContent = row.value;

            wrapper.append(label, value);
            modalMeta.append(wrapper);
        });

        modalMeta.classList.toggle('hidden', rows.length === 0);
    };

    const renderDescription = function (description) {
        modalDescription.replaceChildren();

        // Paragraf dipisah oleh baris kosong, jadi narasi panjang tetap rapi.
        const paragraphs = (description || '')
            .split(/\n{2,}/)
            .map(function (block) {
                return block.trim();
            })
            .filter(Boolean);

        modalDescription.classList.toggle('hidden', paragraphs.length === 0);

        paragraphs.forEach(function (paragraph) {
            const element = document.createElement('p');
            element.textContent = paragraph;
            modalDescription.append(element);
        });
    };

    const openAchievementModal = function (index) {
        const achievement = ACHIEVEMENTS[index];

        if (!achievement) {
            return;
        }

        if (achievement.image_url) {
            modalImage.src = achievement.image_url;
            modalImage.alt = 'Dokumentasi ' + (achievement.title || 'prestasi');
            modalImage.classList.remove('hidden');
        } else {
            modalImage.classList.add('hidden');
            modalImage.removeAttribute('src');
        }

        document.getElementById('achievement-modal-title').textContent = achievement.title || '';
        setBadge(modalCategory, achievement.category);
        setBadge(modalLevel, achievement.level);
        setBadge(modalDateText, achievement.date_label);
        modalDate.classList.toggle('hidden', !achievement.date_label);

        renderMetaRows([
            { label: 'Nama siswa / tim', value: achievement.name },
            { label: 'Kategori', value: achievement.category },
            { label: 'Tingkat', value: achievement.level },
            { label: 'Waktu / cúp', value: achievement.date_label },
        ]);

        renderDescription(achievement.description);

        modalAttribution.classList.toggle('hidden', !achievement.name);
        modalAttribution.textContent = achievement.name ? 'Peraih: ' + achievement.name : '';

        lastFocusedCard = document.activeElement;
        achievementModal.classList.remove('hidden');
        achievementModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        achievementCloseBtn.focus();
    };

    const closeAchievementModal = function () {
        achievementModal.classList.add('hidden');
        achievementModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';

        if (lastFocusedCard) {
            lastFocusedCard.focus();
        }
    };

    achievementCards.forEach(function (card) {
        card.addEventListener('click', function () {
            openAchievementModal(Number(card.getAttribute('data-achievement-card')));
        });

        card.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openAchievementModal(Number(card.getAttribute('data-achievement-card')));
            }
        });
    });

    achievementCloseBtn.addEventListener('click', closeAchievementModal);
    achievementDoneBtn.addEventListener('click', closeAchievementModal);

    // Klik di area mana pun pada overlay menutup modal, sedangkan klik di
    // dalam isi modal berhenti di sini supaya tidak ikut menutup.
    achievementDialog.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    achievementModal.addEventListener('click', closeAchievementModal);

    document.addEventListener('keydown', function (event) {
        if (achievementModal.classList.contains('hidden')) {
            return;
        }

        if (event.key === 'Tab') {
            const focusableElements = Array.from(achievementModal.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'))
                .filter(function (element) {
                    return element.getClientRects().length > 0;
                });
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (!firstElement) {
                event.preventDefault();
            } else if (event.shiftKey && (document.activeElement === firstElement || !achievementModal.contains(document.activeElement))) {
                event.preventDefault();
                lastElement.focus();
            } else if (!event.shiftKey && (document.activeElement === lastElement || !achievementModal.contains(document.activeElement))) {
                event.preventDefault();
                firstElement.focus();
            }
        }

        if (event.key === 'Escape') {
            closeAchievementModal();
        }
    });
</script>
@endsection
