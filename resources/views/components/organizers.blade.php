@props([
    'group' => 'penyelenggara',
    'title' => 'Support By',
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

    // Logo ditampilkan polos tanpa kartu: hanya gambar, tanpa border dan tanpa
    // nama di bawahnya. Efek grayscale dipakai supaya logo berwarna tidak
    // bersaing dengan isi halaman dan kembali penuh saat hover.
    $logoClass = $isEduroute
        ? 'h-11 w-auto max-w-[10rem] object-contain opacity-70 grayscale transition-all duration-300 hover:opacity-100 hover:grayscale-0 dark:opacity-60 dark:grayscale dark:hover:opacity-100 dark:hover:grayscale-0'
        : 'h-12 w-auto max-w-[10rem] object-contain opacity-70 grayscale transition-all duration-300 hover:opacity-100 hover:grayscale-0 sm:h-14';
@endphp

@if ($organizers->isNotEmpty())
    <section @class([
        'mt-14' => $isEduroute && ! $divided,
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
            'mt-6 flex flex-wrap items-center justify-center gap-x-10 gap-y-6 sm:gap-x-12' => $isEduroute,
            'container-page mx-auto mt-10 flex max-w-5xl flex-wrap items-center justify-center gap-x-10 gap-y-8 sm:gap-x-14' => ! $isEduroute,
        ])>
            @foreach ($organizers as $organizer)
                <li class="min-w-0" data-reveal="up" data-reveal-delay="{{ $loop->index * 75 }}">
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