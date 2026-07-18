@props([
    'label',
    'value',
    'hint' => null,
    'highlight' => false,
])

<div {{ $attributes->merge(['class' => 'bg-surface-container border border-surface-variant rounded-lg p-lg relative overflow-hidden group']) }}>

    @if ($highlight)
        <div class="absolute -top-6 -right-6 w-24 h-24 bg-primary rounded-full blur-2xl opacity-40 motion-safe:animate-glow"></div>
    @else
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-primary/10 transition-all"></div>
    @endif

    <p class="font-mono-label text-label-caps uppercase relative {{ $highlight ? 'text-primary' : 'text-on-surface-variant' }}">
        {{ $label }}
    </p>

    <div class="mt-sm flex items-end gap-sm relative">
        <span class="text-stats-number text-on-surface">{{ $value }}</span>
        @if ($hint)
            <span class="text-on-surface-variant text-body-md mb-1">{{ $hint }}</span>
        @endif
    </div>
</div>