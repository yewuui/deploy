@props([
    'group' => 'penyelenggara',
    'title' => 'Powered by',
    // "school" untuk halaman utama sekolah, "eduroute" untuk microsite EduRoute
    // yang memakai set token dan dukungan dark mode yang berbeda.
    'variant' => 'school',
    // Pemakaian sebagai blok mandiri (misalnya di bawah "Mitra industri")
    // tidak memakai garis pemisah; jarak dari section sebelumnya dibuat
    // oleh margin section itself.
    'divided' => true,
])

@php
    $organizers = \App\Models\Gallery::published()->ordered()->group($group)->get();
    $isEduroute = $variant === 'eduroute';

    // Logo ditampilkan polos tanpa kartu atau nama di bawahnya, dengan warna aslinya.
    $logoClass = $isEduroute
        ? 'h-8 w-auto max-w-[7rem] object-contain transition-transform duration-300 hover:scale-105 sm:h-11 sm:max-w-[10rem]'
        : 'h-12 w-auto max-w-[10rem] object-contain transition-transform duration-300 hover:scale-105 sm:h-14';
@endphp

@if ($organizers->isNotEmpty())
    <section @class([
        'mt-0 sm:mt-14' => $isEduroute && ! $divided,
        'mt-8 border-t border-hairline pt-6 pb-2 dark:border-night-hairline' => $isEduroute && $divided,
        'border-t border-slate-200/80 bg-surface py-12 lg:py-14' => ! $isEduroute,
    ]) aria-labelledby="support-heading">
        <h2 id="support-heading"
            @class([
                'mx-auto text-center text-h2 font-semibold text-ink dark:text-night-ink' => $isEduroute,
                'mx-auto max-w-3xl text-center font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl' => ! $isEduroute,
            ])
            data-reveal="up">
            {{ $title }}
        </h2>

        <ul @class([
            'mx-auto mt-5 grid max-w-sm grid-cols-2 items-center justify-items-center gap-x-3 gap-y-4 sm:mt-6 sm:flex sm:max-w-none sm:flex-wrap sm:justify-center sm:gap-x-12' => $isEduroute,
            'container-page mx-auto mt-8 grid max-w-sm grid-cols-2 items-center justify-items-center gap-x-4 gap-y-6 sm:mt-10 sm:flex sm:max-w-5xl sm:flex-wrap sm:justify-center sm:gap-x-14 sm:gap-y-8' => ! $isEduroute,
        ])>
            @foreach ($organizers as $organizer)
                <li class="flex min-w-0 justify-center last:col-span-2 sm:last:col-auto" data-reveal="up" data-reveal-delay="{{ $loop->index * 75 }}">
                    @if ($organizer->safe_website_url)
                        <a href="{{ $organizer->safe_website_url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-sky focus-visible:ring-offset-2">
                            <img src="{{ $organizer->image_url }}" alt="{{ $organizer->alt }}" loading="lazy" decoding="async"
                                 class="{{ $logoClass }}">
                        </a>
                    @else
                        <img src="{{ $organizer->image_url }}" alt="{{ $organizer->alt }}" loading="lazy" decoding="async"
                             class="{{ $logoClass }}">
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
@endif
