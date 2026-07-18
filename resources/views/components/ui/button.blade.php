@props([
    'variant' => 'primary', // primary | secondary
    'href' => null,          // si viene, se renderiza como <a>, si no, como <button>
])

@php
    $base = 'inline-flex items-center justify-center gap-2 px-lg py-sm rounded font-bold text-body-md transition-all active:scale-[0.98]';

    $variants = [
        'primary'   => 'bg-primary-container text-on-primary-container hover:shadow-[0_0_15px_rgba(245,158,11,0.4)]',
        'secondary' => 'border border-outline text-on-surface hover:border-primary hover:text-primary font-normal',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif