@props([
    'column',       // nombre de la columna en la BD (ej. 'name')
    'label',        // texto que se muestra (ej. 'Sorteo')
    'align' => 'left', // 'left' o 'right', por si alguna columna la necesitas alineada distinto
])

@php
    $isActive = request('sort') === $column;
    $nextDirection = ($isActive && request('direction') === 'asc') ? 'desc' : 'asc';
    $url = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection]);
    $icon = $isActive ? (request('direction') === 'asc' ? 'arrow_upward' : 'arrow_downward') : null;
@endphp

<th {{ $attributes->merge(['class' => 'text-' . $align . ' px-lg py-md font-medium']) }}>
    <a href="{{ $url }}" class="flex items-center gap-1 hover:text-primary transition-colors {{ $align === 'right' ? 'justify-end' : '' }}">
        {{ $label }}
        @if ($icon)
            <span class="material-symbols-outlined text-sm">{{ $icon }}</span>
        @endif
    </a>
</th>
