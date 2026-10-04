@props([
    'stageLabel' => '01',
    'title' => '',
    'description' => null,
    'topics' => [],
    'typeLabel' => null,
    'imageUrl' => null,
    'accent' => [],
    'bar' => 'border-t-blue-500',
])

@php
    $topicList = array_values((array) $topics);
    $dot = $accent['dot'] ?? 'bg-blue-500';
    $number = $accent['number'] ?? 'text-royal-strong dark:text-night-royal';
    $hover = $accent['hover'] ?? 'hover:border-royal/40 dark:hover:border-night-royal/40';
    $soft = $accent['soft'] ?? 'group-hover:bg-blue-100 dark:group-hover:bg-blue-900/60';
@endphp

<button
    type="button"
    data-stage-open
    data-stage-number="{{ $stageLabel }}"
    data-stage-title="{{ $title }}"
    data-stage-description="{{ $description ?? '' }}"
    data-stage-topics="{{ json_encode($topicList, JSON_UNESCAPED_UNICODE) }}"
    data-stage-type-label="{{ $typeLabel ?? '' }}"
    data-stage-image=""
    data-stage-bar="{{ $bar }}"
    aria-haspopup="dialog"
    aria-label="Tahap {{ $stageLabel }}: {{ $title }}. {{ $typeLabel ? $typeLabel . '. ' : '' }}Lihat rincian."
    {{ $attributes->merge(['class' => 'group flex w-full flex-col rounded-card border border-hairline dark:border-night-hairline border-t-[3px] '.$bar.' bg-paper dark:bg-night-surface p-4 text-left shadow-soft transition-colors duration-200 '.$hover.' focus:outline-none focus-visible:ring-2 focus-visible:ring-royal dark:focus-visible:ring-night-royal focus-visible:ring-offset-2 dark:focus-visible:ring-offset-night-canvas']) }}
>
    
    <span class="flex items-center gap-2" aria-hidden="true">
        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dot }}"></span>
        <span class="text-micro font-bold tabular-nums {{ $number }}">{{ $stageLabel }}</span>
        <span class="h-px flex-1 bg-hairline dark:bg-night-hairline transition-colors duration-200 {{ $soft }}"></span>
    </span>
    @if($typeLabel)
        <span class="mt-2 inline-flex max-w-full self-start rounded-item bg-surface px-2 py-1 text-micro font-semibold text-royal-strong dark:bg-night-raised dark:text-night-royal">{{ $typeLabel }}</span>
    @endif
    <span class="mt-2.5 block text-[13px] font-semibold leading-snug break-words text-ink dark:text-night-ink">{{ $title }}</span>
    <span class="mt-1 block text-micro text-muted dark:text-night-muted">Buka tahap ini untuk melihat {{ $typeLabel ? 'rincian kegiatan' : 'rincian materi' }}.</span>
    <span class="mt-2.5 inline-flex items-center gap-1.5 border-t border-hairline pt-2.5 text-micro font-semibold text-royal-strong transition-colors duration-200 group-hover:text-royal dark:border-night-hairline dark:text-night-royal dark:group-hover:text-night-royal">
        Lihat rincian
        <svg class="w-3 h-3 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
    </span>
</button>
