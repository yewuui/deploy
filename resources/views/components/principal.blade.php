@props([
    'name' => null,
    'image' => null,
])

@php
    $principal = \App\Support\Site::principal();
    $principalName = $name ?: $principal['name'];
    $principalMessage = $slot->isEmpty() ? $principal['message'] : $slot;
    $principalMessageText = trim(strip_tags((string) $principalMessage));
    $hasLongPrincipalMessage = mb_strlen($principalMessageText) > 280;
    $principalMessagePreview = \Illuminate\Support\Str::limit($principalMessageText, 280);
@endphp

<section {{ $attributes->merge(['class' => 'section']) }}>
    <div class="container-page">
        <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5" data-reveal="right">
                <div class="relative mx-auto max-w-sm lg:mx-0">
                    <div class="absolute -bottom-4 -right-4 h-full w-full rounded-2xl bg-surface-muted" aria-hidden="true"></div>
                    <img src="{{ $image ?: $principal['photo'] }}" alt="Foto {{ $principalName }}" loading="lazy"
                         class="relative aspect-[4/5] w-full rounded-2xl object-cover shadow-card ring-1 ring-slate-900/5">
                </div>
            </div>

            <div class="lg:col-span-7" data-reveal="left">
                <p class="eyebrow">Sambutan</p>
                <h2 class="mt-4 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Kepala Sekolah
                </h2>

                <figure class="mt-6 border-l-4 border-brand-accent pl-6">
                    <blockquote x-data="{ expanded: false }" class="text-base leading-relaxed text-slate-600 sm:text-lg">
                        @if ($hasLongPrincipalMessage)
                            <p x-show="!expanded">{{ $principalMessagePreview }}</p>
                            <p x-show="expanded" x-cloak>{{ $principalMessageText }}</p>
                            <button type="button" class="mt-2 text-sm font-semibold text-brand-sky hover:text-brand-navy" @click="expanded = !expanded">
                                <span x-text="expanded ? 'Tampilkan lebih sedikit' : 'Baca selengkapnya'">Baca selengkapnya</span>
                            </button>
                        @else
                            <p>{{ $principalMessageText }}</p>
                        @endif
                    </blockquote>
                    <figcaption class="mt-6">
                        <p class="font-display text-base font-bold text-slate-900">{{ $principalName }}</p>
                        <p class="text-sm text-slate-500">Kepala {{ \App\Support\Site::name() }}</p>
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>
</section>
