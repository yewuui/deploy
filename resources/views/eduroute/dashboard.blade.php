@extends('layouts.eduroute', ['activeMenu' => 'dashboard'])

@php
    $seoTitle = 'EduRoute '.$program->short_name;
    $seoDescription = 'Beranda EduRoute '.$program->name.' SMKN 1 Bantul. Lihat kurikulum, guru, dan mitra industri program keahlian ini.';
@endphp

@section('content')
@php
    $teacherPlaceholderPhoto = asset('images/eduroute/guru/placeholder-guru-rpl.svg');

    // Tilen ikon untuk kartu "Fokus pembelajaran". Dipetakan dari kata kunci
    // judul highlight; bila tidak ada yang cocok, memakai rotasi ikon tetap.
    // Murni presentasi — data highlight tidak diubah.
    $highlightIcons = [
        'code' => 'M8 9 4 12l4 3m8-6 4 3-4 3M14 5l-4 14',
        'smartphone' => 'M7 2.5h10a2.5 2.5 0 0 1 2.5 2.5v14a2.5 2.5 0 0 1-2.5 2.5H7A2.5 2.5 0 0 1 4.5 19V5A2.5 2.5 0 0 1 7 2.5ZM11 18.5h2',
        'database' => 'M12 2.5c4.4 0 8 1.34 8 3s-3.6 3-8 3-8-1.34-8-3 3.6-3 8-3ZM4 5.5v7c0 1.66 3.58 3 8 3s8-1.34 8-3v-7M4 12.5v7c0 1.66 3.58 3 8 3s8-1.34 8-3v-7',
        'pen' => 'm14.5 5.5 4 4L8 20l-5 1 1-5L14.5 5.5Zm-1.5 1.5 4 4',
        'layers' => 'm12 3 9 5-9 5-9-5 9-5Zm-9 10 9 5 9-5',
    ];
    $highlightIconKeys = ['code', 'smartphone', 'database', 'pen'];
@endphp

<div class="mx-auto w-full max-w-[1200px]">
    <section class="relative isolate flex min-h-[20rem] items-center overflow-hidden rounded-hero border border-royal/20 px-6 py-8 shadow-soft mb-10 sm:min-h-[24rem] sm:px-8 lg:px-10" aria-labelledby="program-intro-title" data-reveal="up">
        <div class="absolute inset-0 overflow-hidden bg-[#142b55]" aria-hidden="true">
            <video class="absolute inset-0 h-full w-full object-cover object-center" src="{{ asset('vidio/1003.mp4') }}" autoplay muted loop playsinline preload="metadata"></video>
            <div class="absolute inset-0 bg-gradient-to-r from-black/98 via-black/70 to-transparent"></div>
        </div>
        <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-blue-300 via-amber-300 to-red-500" aria-hidden="true"></div>

        <div class="relative max-w-2xl">
            <p class="text-micro font-semibold uppercase tracking-wider text-blue-200 mb-3" data-reveal="up">SMKN 1 Bantul &middot; Jurusan {{ $program->name }}</p>
            <h2 id="program-intro-title" class="text-h1 font-bold text-white mb-4" data-reveal="up" data-reveal-delay="100">
                {{ $program->hero_title ?? $program->name . ' (' . $program->short_name . ')' }}
            </h2>
            @if($program->hero_text ?? $program->description)
                <p class="text-body text-white/85 max-w-xl" data-reveal="up" data-reveal-delay="150">
                    {{ $program->hero_text ?? $program->description }}
                </p>
            @endif
        </div>
    </section>

    @if(! empty($program->highlights))
        <section class="mb-10" aria-labelledby="rpl-focus-title">
            <div class="mb-5" data-reveal="up">
                <div class="inline-flex flex-col items-start">
                    <h3 id="rpl-focus-title" class="text-h2 font-semibold text-ink dark:text-night-ink">Fokus pembelajaran</h3>
                    <span class="mt-2 block h-0.5 w-full min-w-[4rem] rounded-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                </div>
                <p class="mt-3 max-w-3xl text-small text-muted dark:text-night-muted">Bidang utama yang dipelajari siswa di jurusan {{ $program->short_name }}.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach($program->highlights as $highlight)
                    @php
                        $haystack = strtolower($highlight['title'] ?? '');
                        $iconKey = null;
                        foreach ([['web', 'code'], ['mobile', 'smartphone'], ['data', 'database'], ['design', 'pen'], ['ui', 'pen']] as $rule) {
                            if ($iconKey === null && str_contains($haystack, $rule[0])) {
                                $iconKey = $rule[1];
                            }
                        }
                        $iconKey = $iconKey ?? $highlightIconKeys[$loop->index % count($highlightIconKeys)];
                    @endphp
                    <div class="hover-lift flex h-full flex-col rounded-card border border-hairline bg-paper p-5 shadow-soft dark:border-night-hairline dark:bg-night-surface" data-reveal="up" data-reveal-delay="{{ min(($loop->index + 1) * 100, 400) }}">
                        <span class="mb-4 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-item bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $highlightIcons[$iconKey] }}"></path></svg>
                        </span>
                        <h4 class="text-small font-semibold text-ink dark:text-night-ink">{{ $highlight['title'] }}</h4>
                        <p class="mt-1.5 text-small text-muted dark:text-night-muted">{{ $highlight['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mb-10" aria-labelledby="rpl-grades-title">
        <div class="mb-5" data-reveal="up">
            <div class="inline-flex flex-col items-start">
                <h3 id="rpl-grades-title" class="text-h2 font-semibold text-ink dark:text-night-ink">Kurikulum per kelas</h3>
                <span class="mt-2 block h-0.5 w-full min-w-[4rem] rounded-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
            </div>
            <p class="mt-3 max-w-3xl text-small text-muted dark:text-night-muted">Materi tiap kelas disusun per semester. Pilih kelas untuk melihat alur pembelajarannya.</p>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @foreach ([10, 11, 12] as $grade)
                <a href="{{ route('eduroute.roadmap', ['program' => $program, 'grade' => $grade]) }}" data-reveal="up" data-reveal-delay="{{ min(($loop->index + 1) * 100, 300) }}" class="group hover-lift flex min-h-[44px] items-center justify-between gap-3 rounded-card border border-hairline bg-paper px-5 py-4 shadow-soft hover:border-royal/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-royal focus-visible:ring-offset-2 dark:border-night-hairline dark:bg-night-surface dark:hover:border-night-royal/50 dark:focus-visible:ring-offset-night-canvas">
                    <span class="flex items-center gap-3.5">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-item text-body font-bold tabular-nums {{ $grade === 12 ? 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-200' : 'bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal' }}" aria-hidden="true">{{ $grade }}</span>
                        <span>
                            <span class="block text-small font-semibold text-ink dark:text-night-ink">Kelas {{ $grade }}</span>
                            <span class="mt-0.5 block text-micro text-muted dark:text-night-muted">{{ $grade === 12 ? 'Program & kegiatan' : 'Semester 1&ndash;2' }}</span>
                        </span>
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-royal-strong transition-transform duration-200 group-hover:translate-x-0.5 dark:text-night-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @endforeach
        </div>
    </section>

    @if($teachers->isNotEmpty())
        <section class="mb-10" aria-labelledby="rpl-teachers-title">
            <div class="mb-5" data-reveal="up">
                <div class="inline-flex flex-col items-start">
                    <h3 id="rpl-teachers-title" class="text-h2 font-semibold text-ink dark:text-night-ink">
                        Guru {{ $program->short_name }}
                    </h3>
                    <span class="mt-2 block h-0.5 w-full min-w-[4rem] rounded-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                </div>
                <p class="mt-3 max-w-3xl text-small text-muted dark:text-night-muted">
                    Guru yang mengampu pembelajaran di Jurusan {{ $program->name }} SMKN 1 Bantul. Pilih kartu guru untuk melihat profil lengkapnya.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($teachers as $teacher)
                    @php($hasDetails = $teacher->hasProfileDetails())
                    <article
                        @if($hasDetails)
                            data-teacher-card="{{ $loop->index }}" tabindex="0" role="button" aria-haspopup="dialog" aria-label="Lihat profil {{ $teacher->name }}"
                        @endif
                        data-reveal="up"
                        data-reveal-delay="{{ min(($loop->index % 3 + 1) * 100, 300) }}"
                        class="flex h-full min-w-0 flex-col overflow-hidden rounded-card border border-hairline bg-paper shadow-soft dark:border-night-hairline dark:bg-night-surface {{ $hasDetails ? 'hover-lift cursor-pointer hover:border-royal/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-royal focus-visible:ring-offset-2 dark:hover:border-night-royal/50 dark:focus-visible:ring-offset-night-canvas' : '' }}"
                    >
                        <div class="relative aspect-[4/3] w-full overflow-hidden bg-surface dark:bg-night-raised">
                            <img
                                src="{{ $teacher->photo_url ?? $teacherPlaceholderPhoto }}"
                                alt="Foto {{ $teacher->name }}, {{ $teacher->subject }}"
                                loading="lazy"
                                decoding="async"
                                class="h-full w-full object-cover"
                                onerror="this.onerror=null; this.src='{{ $teacherPlaceholderPhoto }}';"
                            >
                        </div>

                        <div class="flex flex-1 flex-col p-5">
                            <h4 class="text-small font-semibold leading-snug text-ink dark:text-night-ink">
                                {{ $teacher->name }}
                            </h4>
                            @if($teacher->role_title)
                                <p class="mt-0.5 text-[13px] font-semibold leading-snug text-royal-strong dark:text-night-royal">
                                    {{ $teacher->role_title }}
                                </p>
                                <p class="mt-1 text-[13px] font-medium leading-snug text-muted dark:text-night-muted">
                                    {{ $teacher->subject }}
                                </p>
                            @else
                                <p class="mt-1 text-[13px] font-medium leading-snug text-royal-strong dark:text-night-royal">
                                    {{ $teacher->subject }}
                                </p>
                            @endif
                            @if($teacher->description)
                                {{-- Dibatasi tiga baris agar kartu tetap rapi dan sejajar;
                                    teks lengkapnya baru muncul saat profil dibuka. --}}
                                <p class="mt-2.5 flex-1 text-small text-muted line-clamp-3 dark:text-night-muted">
                                    {{ $teacher->description }}
                                </p>
                            @else
                                <span class="flex-1"></span>
                            @endif
                            @if($hasDetails)
                                <span class="mt-4 inline-flex items-center gap-1 text-micro font-semibold text-royal-strong dark:text-night-royal" aria-hidden="true">
                                    Lihat profil
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                                </span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($teachers->isEmpty())
        <section class="mb-10" aria-labelledby="rpl-teachers-title" data-reveal="up">
            <div class="rounded-card border border-hairline bg-paper p-10 text-center shadow-soft dark:border-night-hairline dark:bg-night-surface">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M9.5 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm9.5 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <p class="text-small font-semibold text-ink dark:text-night-ink">Segera Hadir</p>
            </div>
        </section>
    @endif

    @if($partners->isNotEmpty())
        <section class="pb-4">
            <div class="mb-6 text-center" data-reveal="up">
                <div class="inline-flex flex-col items-center">
                    <h3 class="text-h2 font-semibold text-ink dark:text-night-ink">Mitra industri</h3>
                    <span class="mt-2 block h-0.5 w-full min-w-[4rem] rounded-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
                </div>
                <p class="mt-3 text-small text-muted dark:text-night-muted">Industri yang bekerja sama dengan Jurusan {{ $program->short_name }} SMKN 1 Bantul</p>
            </div>
            <div class="grid w-full grid-cols-2 items-stretch gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                @foreach ($partners as $partner)
                    <div data-reveal="zoom" data-reveal-delay="{{ min(($loop->index % 6 + 1) * 60, 400) }}" class="flex">
                        <x-partner-card
                            :name="$partner->name"
                            :logo-url="$partner->logo_url"
                            :website-url="$partner->website_url"
                            :bg-color="$partner->logo_bg_color_effective"
                            :text-color="$partner->logo_bg_text_color"
                        />
                    </div>
                @endforeach
            </div>
        </section>
    @elseif($partners->isEmpty())
        <section class="pb-4" data-reveal="up">
            <div class="rounded-card border border-hairline bg-paper p-10 text-center shadow-soft dark:border-night-hairline dark:bg-night-surface">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7zm0 4h16"/></svg>
                </div>
                <p class="text-small font-semibold text-ink dark:text-night-ink">Segera Hadir</p>
            </div>
        </section>
    @endif

    <x-organizers variant="eduroute" :divided="false" />
</div>

@if($teachers->isNotEmpty())
    @php($teacherProfileData = $teachers->map(fn ($teacher) => [
        'name' => $teacher->name,
        'role' => $teacher->role_title,
        'subject' => $teacher->subject,
        'photo' => $teacher->photo_url ?? $teacherPlaceholderPhoto,
        'description' => $teacher->description,
        'expertise' => $teacher->expertise,
        'education' => $teacher->education,
        'campus' => $teacher->campus,
    ])->values())

    <div id="teacher-modal" class="hidden fixed inset-0 z-[60]" aria-hidden="true">
        <div id="teacher-modal-backdrop" class="absolute inset-0 bg-night-canvas/60 backdrop-blur-sm"></div>

        <div class="relative flex min-h-full items-center justify-center p-4">
            <div role="dialog" aria-modal="true" aria-labelledby="teacher-modal-name" class="relative flex max-h-[88vh] w-full max-w-2xl flex-col overflow-hidden rounded-card border border-hairline bg-paper shadow-lift dark:border-night-hairline dark:bg-night-surface">
                {{-- Aksen lembut di puncak panel, selaras dengan hero halaman. --}}
                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-royal via-blue-400 to-red-500 dark:from-night-royal dark:to-red-400" aria-hidden="true"></span>

                <button id="teacher-modal-close" type="button" aria-label="Tutup profil guru" class="absolute right-3 top-3 z-10 flex h-11 w-11 items-center justify-center rounded-item text-muted transition-colors duration-200 hover:bg-surface hover:text-ink dark:text-night-muted dark:hover:bg-night-raised dark:hover:text-night-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="overflow-y-auto">
                    {{-- Identitas: foto persegi di kiri, nama dan peran di kanan. --}}
                    <div class="flex flex-col gap-4 p-5 pr-16 sm:flex-row sm:gap-5 sm:p-6 sm:pr-16">
                        <div class="h-28 w-28 shrink-0 overflow-hidden rounded-item bg-surface sm:h-32 sm:w-32 dark:bg-night-raised">
                            <img id="teacher-modal-photo" src="" alt="" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 id="teacher-modal-name" class="text-h2 font-bold leading-tight text-ink dark:text-night-ink"></h3>
                            <p id="teacher-modal-role" class="mt-1 text-small font-semibold leading-snug text-royal-strong dark:text-night-royal"></p>
                        </div>
                    </div>

                    <div class="border-t border-hairline px-5 py-5 sm:px-6 dark:border-night-hairline">
                        {{-- Mengikuti susunan pada referensi desain: jabatan di bawah
                            nama, lalu bidang keahlian sebagai subjudul tebal,
                            deskripsi, kemudian blok pendidikan. --}}
                        <h4 id="teacher-modal-subject-heading" class="text-body font-bold leading-snug text-ink dark:text-night-ink"></h4>
                        <p id="teacher-modal-description" class="mt-2 text-small text-muted dark:text-night-muted"></p>

                        <div id="teacher-modal-expertise-block" class="mt-5 hidden">
                            <h5 class="text-micro font-semibold uppercase tracking-wider text-muted dark:text-night-muted">Bidang Keahlian</h5>
                            <p id="teacher-modal-expertise" class="mt-1.5 text-small font-semibold text-ink dark:text-night-ink"></p>
                        </div>

                        <div id="teacher-modal-education-block" class="mt-5 hidden">
                            <h5 class="text-small font-bold text-ink dark:text-night-ink">Pendidikan Terakhir</h5>
                            <p id="teacher-modal-education" class="mt-1 text-small font-medium text-ink dark:text-night-ink"></p>
                            <p id="teacher-modal-campus" class="text-small text-muted dark:text-night-muted"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('page-scripts')
@if($teachers->isNotEmpty())
<script>
    const TEACHERS = @json($teacherProfileData);
    const teacherModal = document.getElementById('teacher-modal');
    const teacherDialog = teacherModal.querySelector('[role="dialog"]');
    const teacherCloseBtn = document.getElementById('teacher-modal-close');
    const teacherCards = document.querySelectorAll('[data-teacher-card]');
    let lastFocusedTeacherCard = null;

    const setTeacherText = function(elementId, value) {
        const element = document.getElementById(elementId);
        element.textContent = value || '';
        return element;
    };

    const openTeacherModal = function(index) {
        const teacher = TEACHERS[index];
        if (!teacher) {
            return;
        }

        const photo = document.getElementById('teacher-modal-photo');
        photo.src = teacher.photo || '';
        photo.alt = 'Foto ' + teacher.name;

        setTeacherText('teacher-modal-name', teacher.name);
        setTeacherText('teacher-modal-role', teacher.role);
        setTeacherText('teacher-modal-subject-heading', teacher.subject);
        setTeacherText('teacher-modal-description', teacher.description);

        const role = document.getElementById('teacher-modal-role');
        role.classList.toggle('hidden', !teacher.role);
        const subjectHeading = document.getElementById('teacher-modal-subject-heading');
        subjectHeading.classList.toggle('hidden', !teacher.subject);
        const description = document.getElementById('teacher-modal-description');
        description.classList.toggle('hidden', !teacher.description);

        setTeacherText('teacher-modal-expertise', teacher.expertise);
        document.getElementById('teacher-modal-expertise-block').classList.toggle('hidden', !teacher.expertise);

        setTeacherText('teacher-modal-education', teacher.education);
        setTeacherText('teacher-modal-campus', teacher.campus);
        document.getElementById('teacher-modal-education-block').classList.toggle('hidden', !teacher.education && !teacher.campus);

        lastFocusedTeacherCard = document.activeElement;
        teacherModal.classList.remove('hidden');
        teacherModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        teacherCloseBtn.focus();
    };

    const closeTeacherModal = function() {
        teacherModal.classList.add('hidden');
        teacherModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (lastFocusedTeacherCard) {
            lastFocusedTeacherCard.focus();
        }
    };

    teacherCards.forEach(function(card) {
        card.addEventListener('click', function() {
            openTeacherModal(Number(card.getAttribute('data-teacher-card')));
        });
        card.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openTeacherModal(Number(card.getAttribute('data-teacher-card')));
            }
        });
    });

    teacherCloseBtn.addEventListener('click', closeTeacherModal);

    // Klik di area mana pun pada overlay menutup modal, sedangkan klik di
    // dalam isi modal berhenti di sini supaya tidak ikut menutup.
    teacherDialog.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    teacherModal.addEventListener('click', closeTeacherModal);

    document.addEventListener('keydown', function(event) {
        if (teacherModal.classList.contains('hidden')) {
            return;
        }

        if (event.key === 'Tab') {
            const focusableElements = Array.from(teacherModal.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'))
                .filter(function (element) {
                    return element.getClientRects().length > 0;
                });
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (!firstElement) {
                event.preventDefault();
            } else if (event.shiftKey && (document.activeElement === firstElement || !teacherModal.contains(document.activeElement))) {
                event.preventDefault();
                lastElement.focus();
            } else if (!event.shiftKey && (document.activeElement === lastElement || !teacherModal.contains(document.activeElement))) {
                event.preventDefault();
                firstElement.focus();
            }
        }

        if (event.key === 'Escape') {
            closeTeacherModal();
        }
    });
</script>
@endif
@endsection