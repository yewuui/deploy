@props([
    'name',
    'logoUrl' => null,
    'websiteUrl' => null,
    'bgColor' => '#FFFFFF',
    'textColor' => '#111827',
])

@php
    // Warna latar kotak logo datang dari admin (hex hasil normalisasi RGB/HEX)
    // dan dipasang sebagai inline style supaya warnanya sama persis dengan
    // yang dipilih, di tema terang maupun gelap. Nilai warna selalu hex 6
    // digit, jadi aman disisipkan langsung ke style tanpa disanitasi ulang.
    $tag = $websiteUrl ? 'a' : 'div';
@endphp

<{{ $tag }}@if($websiteUrl) href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer"@endif
    data-partner-card
    title="{{ $name }}"
    class="flex min-h-28 w-full min-w-0 flex-col overflow-hidden rounded-card border border-hairline bg-paper shadow-soft transition-colors duration-200 hover:border-royal/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-royal focus-visible:ring-offset-2 dark:border-night-hairline dark:bg-night-surface dark:hover:border-night-royal/40 dark:focus-visible:ring-offset-night-canvas"
>
    <span class="flex h-20 w-full items-center justify-center rounded-t-card px-2 py-2 ring-1 ring-inset ring-black/5 dark:ring-white/10" style="background-color: {{ $bgColor }};">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo {{ $name }}" class="max-h-16 w-auto max-w-full object-contain object-center" loading="lazy" decoding="async" onerror="this.onerror=null;this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden');">
            <span data-logo-fallback class="hidden text-center text-small font-semibold" style="color: {{ $textColor }};">{{ $name }}</span>
        @else
            <span class="text-center text-small font-semibold" style="color: {{ $textColor }};">{{ $name }}</span>
        @endif
    </span>
    <span class="flex flex-1 items-center justify-center border-t border-hairline px-2 py-1.5 text-center text-micro font-medium leading-snug text-muted dark:border-night-hairline dark:text-night-muted">{{ $name }}</span>
</{{ $tag }}>