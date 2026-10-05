@props([
    'name',
    'imageUrl' => null,
    'manager' => null,
    'description' => null,
])

<article {{ $attributes->merge(['class' => 'group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-card ring-1 ring-slate-900/5 transition-shadow duration-300 hover:shadow-card-hover']) }} data-reveal="up">
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-surface-muted">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]">
        @else
            <span class="flex h-full w-full flex-col items-center justify-center gap-2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Gambar belum tersedia</span>
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col justify-center p-4">
        <h3 class="font-display text-[15px] font-semibold leading-snug text-slate-900">{{ $name }}</h3>
        @if ($manager)
            <p class="mt-2 text-xs font-semibold text-brand-navy">Dikelola oleh {{ $manager }}</p>
        @endif
        @if ($description)
            <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $description }}</p>
        @endif
    </div>
</article>
