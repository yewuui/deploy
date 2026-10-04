@extends('layouts.eduroute', ['activeMenu' => 'prospek'])

@php
    $seoTitle = 'Prospek Karier '.$program->short_name;
    $seoDescription = 'Prospek karier dan kompetensi akhir untuk lulusan program keahlian '.$program->name.' SMKN 1 Bantul.';
@endphp

@section('content')
<div class="mx-auto w-full max-w-[1200px]">
    <div class="mb-6" data-reveal="up">
        <div class="inline-flex flex-col items-start">
            <h2 class="text-h2 font-semibold text-ink dark:text-night-ink mb-2">
                Prospek Karier Lulusan {{ $program->short_name }}
            </h2>
            <span class="mb-3 block h-0.5 w-full min-w-[4rem] rounded-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
        </div>
        <p class="mt-3 max-w-3xl text-small text-muted dark:text-night-muted">
            Lulusan {{ $program->name }} memiliki prospek karier yang luas di dunia kerja. Klik setiap kartu untuk melihat detail pekerjaan sehari-hari.
        </p>
    </div>

    @if($careers->isEmpty())
        <div class="rounded-card border border-hairline bg-paper p-10 text-center shadow-soft dark:border-night-hairline dark:bg-night-surface" data-reveal="up">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M12 9v6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg>
            </div>
            <p class="text-small font-semibold text-ink dark:text-night-ink">Segera Hadir</p>
        </div>
    @else
    <div class="grid grid-cols-1 gap-4 pb-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($careers as $career)
            <article data-career-card="{{ $loop->index }}" tabindex="0" role="button" aria-haspopup="dialog" aria-label="Lihat detail {{ $career->title }}" data-reveal="up" data-reveal-delay="{{ min(($loop->index % 3 + 1) * 100, 300) }}" class="hover-lift flex h-full min-w-0 cursor-pointer flex-col rounded-card border border-hairline bg-paper p-5 shadow-soft hover:border-royal/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-royal focus-visible:ring-offset-2 dark:border-night-hairline dark:bg-night-surface dark:hover:border-night-royal/40 dark:focus-visible:ring-offset-night-canvas">
                <div class="career-icon mb-4 flex h-11 w-11 shrink-0 items-center justify-center rounded-item bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal">
                    @php($isUploadedIcon = $career->icon && (str_contains($career->icon, '/') || str_contains($career->icon, '.')))
                    @if($isUploadedIcon && $career->icon_url)
                        <img src="{{ $career->icon_url }}" alt="Ikon {{ $career->title }}" class="w-5 h-5 object-contain" loading="lazy" decoding="async">
                    @else
                    @switch($career->icon)
                        @case('code')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m8 9-4 3 4 3m8-6 4 3-4 3M14 5l-4 14"/></svg>
                            @break
                        @case('layout')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M9 9v11"/></svg>
                            @break
                        @case('server')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="7" rx="2"/><rect x="3" y="13" width="18" height="7" rx="2"/><path d="M7 7.5h.01M7 16.5h.01"/></svg>
                            @break
                        @case('layers')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5"/></svg>
                            @break
                        @case('smartphone')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="7" y="2.5" width="10" height="19" rx="2.5"/><path d="M11 18.5h2"/></svg>
                            @break
                        @case('gamepad')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 8h10a4 4 0 013.9 4.9l-.6 2.7a2.5 2.5 0 01-4.35 1.15L14.6 15H9.4l-1.35 1.75A2.5 2.5 0 013.7 15.6L3.1 12.9A4 4 0 017 8Z"/><path d="M7 11v4M5 13h4M16 12h.01M18 14h.01"/></svg>
                            @break
                        @case('pen')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m14.5 5.5 4 4L8 20l-5 1 1-5L14.5 5.5Z"/><path d="m13 7 4 4"/></svg>
                            @break
                        @case('monitor')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M9 20h6M12 16v4"/></svg>
                            @break
                        @case('database')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v7c0 1.66 3.58 3 8 3s8-1.34 8-3V5M4 12v7c0 1.66 3.58 3 8 3s8-1.34 8-3v-7"/></svg>
                            @break
                        @case('checklist')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
                            @break
                        @case('headset')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 14v-2a8 8 0 0 1 16 0v2"/><rect x="3" y="13" width="4" height="7" rx="1.5"/><rect x="17" y="13" width="4" height="7" rx="1.5"/><path d="M19 20a4 4 0 0 1-4 2h-2"/></svg>
                            @break
                        @case('shield')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3Z"/><path d="m9.5 12 2 2 3.5-3.5"/></svg>
                            @break
                        @case('cloud')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.5 18H7a4.5 4.5 0 1 1 .58-8.97A6 6 0 0 1 19 11a3.5 3.5 0 0 1-1.5 7Z"/></svg>
                            @break
                        @case('bulb')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18h6M10 21h4"/><path d="M12 3a6 6 0 0 0-3.5 10.9c.8.6 1.5 1.4 1.5 2.1h4c0-.7.7-1.5 1.5-2.1A6 6 0 0 0 12 3Z"/></svg>
                            @break
                        @default
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M9 9v11"/></svg>
                    @endswitch
                    @endif
                </div>

                <h3 class="text-small font-semibold leading-snug text-ink dark:text-night-ink">
                    {{ $career->title }}
                </h3>
                <p class="mt-2 flex-1 text-small text-muted dark:text-night-muted">
                    {{ $career->short_description ?? \Illuminate\Support\Str::limit($career->details ?? '', 120) }}
                </p>
                <span class="mt-4 inline-flex items-center gap-1 text-micro font-semibold text-royal-strong dark:text-night-royal" aria-hidden="true">
                    Lihat detail
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </span>
            </article>
        @endforeach
    </div>
    @endif
</div>

<div id="career-modal" class="hidden fixed inset-0 z-[60]" aria-hidden="true">
    <div id="career-backdrop" class="absolute inset-0 bg-night-canvas/60"></div>
    <div class="relative flex min-h-full items-center justify-center p-4">
        <div role="dialog" aria-modal="true" aria-labelledby="career-modal-title" class="w-full max-w-lg rounded-card border border-hairline bg-paper max-h-[85vh] overflow-y-auto shadow-lift dark:border-night-hairline dark:bg-night-surface">
            <div class="flex items-start gap-4 p-6 pb-0">
                <div id="career-modal-icon" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-item bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal"></div>
                <div class="min-w-0 flex-1">
                    <p class="text-micro font-semibold text-muted dark:text-night-muted">Prospek Karier {{ $program->short_name }}</p>
                    <h3 id="career-modal-title" class="mt-0.5 text-body font-semibold leading-snug text-ink dark:text-night-ink"></h3>
                </div>
                <button id="career-modal-close" type="button" aria-label="Tutup detail" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-item text-muted transition-colors duration-200 hover:bg-surface hover:text-ink dark:text-night-muted dark:hover:bg-night-raised dark:hover:text-night-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 pt-4">
                <p id="career-modal-details" class="text-small text-muted dark:text-night-muted"></p>
                <h4 id="career-modal-tasks-heading" class="mt-5 mb-2.5 text-micro font-semibold text-ink dark:text-night-ink">Yang dikerjakan</h4>
                <ul id="career-modal-tasks" class="flex flex-col gap-2"></ul>
                <h4 id="career-modal-tools-heading" class="mt-5 mb-2.5 text-micro font-semibold text-ink dark:text-night-ink">Skill dan tools</h4>
                <div id="career-modal-tools" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    const CAREERS = @json($careers);
    const careerModal = document.getElementById('career-modal');
    const careerDialog = careerModal.querySelector('[role="dialog"]');
    const careerCloseBtn = document.getElementById('career-modal-close');
    const careerCards = document.querySelectorAll('[data-career-card]');
    let lastFocusedCard = null;

    const openCareerModal = function(index) {
        const career = CAREERS[index];
        if (!career) {
            return;
        }

        const cardIcon = careerCards[index].querySelector('.career-icon');
        document.getElementById('career-modal-icon').innerHTML = cardIcon ? cardIcon.innerHTML : '';
        document.getElementById('career-modal-title').textContent = career.title;
        document.getElementById('career-modal-details').textContent = career.details || '';

        const tasks = career.tasks || [];
        document.getElementById('career-modal-tasks-heading').style.display = tasks.length ? '' : 'none';
        const tasksList = document.getElementById('career-modal-tasks');
        tasksList.innerHTML = '';
        tasks.forEach(function(task) {
            const item = document.createElement('li');
            item.className = 'flex items-start gap-2.5 text-small text-muted dark:text-night-muted';
            item.innerHTML = '<svg class="w-4 h-4 mt-0.5 shrink-0 text-leaf dark:text-night-leaf" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg><span></span>';
            item.querySelector('span').textContent = task;
            tasksList.appendChild(item);
        });

        const tools = career.tools || [];
        document.getElementById('career-modal-tools-heading').style.display = tools.length ? '' : 'none';
        const toolsWrap = document.getElementById('career-modal-tools');
        toolsWrap.innerHTML = '';
        tools.forEach(function(tool) {
            const chip = document.createElement('span');
            chip.className = 'px-2.5 py-1 rounded-md text-micro font-semibold bg-leaf-soft text-leaf-strong dark:bg-night-leaf-soft dark:text-night-leaf';
            chip.textContent = tool;
            toolsWrap.appendChild(chip);
        });

        lastFocusedCard = document.activeElement;
        careerModal.classList.remove('hidden');
        careerModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        careerCloseBtn.focus();
    };

    const closeCareerModal = function() {
        careerModal.classList.add('hidden');
        careerModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (lastFocusedCard) {
            lastFocusedCard.focus();
        }
    };

    careerCards.forEach(function(card) {
        card.addEventListener('click', function() {
            openCareerModal(Number(card.getAttribute('data-career-card')));
        });
        card.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openCareerModal(Number(card.getAttribute('data-career-card')));
            }
        });
    });

    careerCloseBtn.addEventListener('click', closeCareerModal);

    // Klik di area mana pun pada overlay menutup modal, sedangkan klik di
    // dalam isi modal berhenti di sini supaya tidak ikut menutup.
    careerDialog.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    careerModal.addEventListener('click', closeCareerModal);

    document.addEventListener('keydown', function(event) {
        if (careerModal.classList.contains('hidden')) {
            return;
        }

        if (event.key === 'Tab') {
            const focusableElements = Array.from(careerModal.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'))
                .filter(function (element) {
                    return element.getClientRects().length > 0;
                });
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (!firstElement) {
                event.preventDefault();
            } else if (event.shiftKey && (document.activeElement === firstElement || !careerModal.contains(document.activeElement))) {
                event.preventDefault();
                lastElement.focus();
            } else if (!event.shiftKey && (document.activeElement === lastElement || !careerModal.contains(document.activeElement))) {
                event.preventDefault();
                firstElement.focus();
            }
        }

        if (event.key === 'Escape') {
            closeCareerModal();
        }
    });
</script>
@endsection
