@extends('layouts.eduroute', ['activeMenu' => 'media'])

@php
    $seoTitle = 'Media Sosial '.$program->short_name;
    $seoDescription = 'Kanal media sosial resmi program keahlian '.$program->name.' SMKN 1 Bantul.';
@endphp

@section('content')
@php
    $mediaIcons = [
        'website' => 'website',
        'instagram' => 'instagram',
        'youtube' => 'youtube',
        'github' => 'github',
        'linkedin' => 'linkedin',
        'tiktok' => 'tiktok',
        'facebook' => 'facebook',
    ];

    // Tilen ikon per platform. Website memakai royal blue, Instagram memakai
    // aksen hangat, sisanya netral. Hanya presentasi.
    $mediaTiles = [
        'website' => 'bg-royal-soft text-royal-strong dark:bg-night-royal-soft dark:text-night-royal',
        'instagram' => 'bg-gold-soft text-gold-strong dark:bg-night-gold-soft dark:text-night-gold',
    ];
@endphp

<div class="mx-auto w-full max-w-[1200px]">
    <div class="mb-6" data-reveal="up">
        <div class="inline-flex flex-col items-start">
            <h2 class="text-h2 font-semibold text-ink dark:text-night-ink mb-2">
                Media Sosial Jurusan {{ $program->short_name }}
            </h2>
            <span class="mb-3 block h-0.5 w-full min-w-[4rem] rounded-pill bg-royal dark:bg-night-royal" aria-hidden="true"></span>
        </div>
        <p class="mt-3 max-w-3xl text-small text-muted dark:text-night-muted">
            Kanal media sosial Jurusan {{ $program->name }} SMKN 1 Bantul untuk aktivitas, portofolio, dan komunitas.
        </p>
    </div>

    @if($socialMedia->isEmpty())
        <div class="rounded-card border border-hairline bg-paper p-6 text-center shadow-soft dark:border-night-hairline dark:bg-night-surface sm:p-10" data-reveal="up">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-surface text-muted dark:bg-night-raised dark:text-night-muted">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            </div>
            <p class="text-small font-semibold text-ink dark:text-night-ink">Segera Hadir</p>
        </div>
    @else
        <div id="social-grid" class="grid grid-cols-1 items-start gap-4 pb-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($socialMedia as $item)
                @php
                    $iconKey = $mediaIcons[strtolower($item->platform)] ?? 'website';
                    $tileClass = $mediaTiles[$iconKey] ?? 'bg-surface text-muted dark:bg-night-raised dark:text-night-muted';
                @endphp
                <article data-reveal="up" data-reveal-delay="{{ min(($loop->index % 3 + 1) * 100, 300) }}" class="flex h-full min-w-0 flex-col rounded-card border border-hairline bg-paper p-5 shadow-soft dark:border-night-hairline dark:bg-night-surface">
                    @if($item->image_url)
                        <div class="mb-4 h-32 w-full overflow-hidden rounded-item bg-surface dark:bg-night-raised">
                            <img src="{{ $item->image_url }}" alt="Gambar {{ $item->platform }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
                        </div>
                    @endif

                    <div class="mb-4 flex items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-item {{ $tileClass }}">
                            @switch($iconKey)
                                @case('website')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.9 5.7 3.9 9s-1.4 6.4-3.9 9c-2.5-2.6-3.9-5.7-3.9-9S9.5 5.6 12 3Z"/></svg>
                                    @break
                                @case('instagram')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2.5" y="2.5" width="19" height="19" rx="5.5"/><circle cx="12" cy="12" r="4.25"/><circle cx="17.6" cy="6.4" r="1.3" fill="currentColor" stroke="none"/></svg>
                                    @break
                                @case('youtube')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23 12s0-3.85-.46-5.58a2.9 2.9 0 0 0-2-2C18.75 4 12 4 12 4s-6.75 0-8.54.42a2.9 2.9 0 0 0-2 2C1 8.15 1 12 1 12s0 3.85.46 5.58a2.9 2.9 0 0 0 2 2c1.79.42 8.54.42 8.54.42s6.75 0 8.54-.42a2.9 2.9 0 0 0 2-2C23 15.85 23 12 23 12Zm-13.2 3.27V8.73L15.5 12l-5.7 3.27Z"/></svg>
                                    @break
                                @case('github')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.55v-2.15c-3.2.7-3.87-1.36-3.87-1.36-.52-1.33-1.28-1.68-1.28-1.68-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.18 1.76 1.18 1.03 1.76 2.7 1.25 3.36.96.1-.75.4-1.25.72-1.54-2.55-.29-5.24-1.28-5.24-5.68 0-1.26.45-2.28 1.18-3.09-.12-.29-.51-1.46.11-3.05 0 0 .96-.31 3.15 1.18a10.9 10.9 0 0 1 5.74 0c2.19-1.49 3.15-1.18 3.15-1.18.62 1.59.23 2.76.11 3.05.74.81 1.18 1.83 1.18 3.09 0 4.41-2.69 5.38-5.25 5.67.41.35.77 1.05.77 2.12v3.14c0 .3.21.67.8.55A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/></svg>
                                    @break
                                @case('linkedin')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.12 20.45H3.55V9h3.57v11.45Z"/></svg>
                                    @break
                                @case('tiktok')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M16.6 3c.4 2.2 1.8 3.6 4.1 3.7v3.1c-1.5 0-2.9-.5-4.1-1.3v6.4a5.9 5.9 0 1 1-5.9-5.9c.3 0 .7 0 1 .1v3.2a2.7 2.7 0 1 0 1.8 2.6V3h3.1Z"/></svg>
                                    @break
                                @case('facebook')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.09 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.7 4.53-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.26h3.33l-.53 3.49h-2.8V24C19.61 23.09 24 18.1 24 12.07Z"/></svg>
                                    @break
                            @endswitch
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-small font-semibold leading-snug text-ink dark:text-night-ink">
                                {{ $item->platform }}
                            </h3>
                            @if($item->handle)
                                <p class="mt-0.5 break-words text-small font-medium leading-snug text-muted dark:text-night-muted">
                                    {{ $item->handle }}
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($item->description)
                        <p class="flex-1 text-small text-muted dark:text-night-muted">
                            {{ $item->description }}
                        </p>
                    @endif

                    @if($item->url)
                        <a
                            href="{{ $item->url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Kunjungi {{ $item->platform }} — membuka tab baru"
                            class="mt-5 flex min-h-[44px] w-full items-center justify-center gap-2 rounded-pill bg-royal px-4 py-2.5 text-small font-semibold text-white transition-colors duration-200 hover:bg-royal-strong focus:outline-none focus-visible:ring-2 focus-visible:ring-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-surface"
                        >
                            Kunjungi {{ $item->platform }}
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
