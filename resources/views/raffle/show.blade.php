@extends('layouts.main')

@section('title', $raffle->name)

@section('content')
@php
    $badge = $raffle->statusBadge();
    $sold = $raffle->tickets_sold_percentage ?? 0;
    $threshold = $raffle->draw_trigger_percentage ?? config('raffle.draw_trigger_percentage', 80);
@endphp

{{-- Encabezado --}}
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md mb-xl border-b border-outline-variant pb-md">
    <div>
        <a href="{{ route('raffle.index') }}" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">← Volver a sorteos</a>
        <div class="flex items-center gap-md mt-sm">
            <h2 class="text-display-lg-mobile md:text-display-lg font-bold text-on-surface tracking-tight">{{ $raffle->name }}</h2>
            <span class="inline-flex items-center gap-1.5 border px-2 py-1 rounded font-mono-label text-label-caps {{ $badge['classes'] }}">
                <span class="material-symbols-outlined text-sm">{{ $badge['icon'] }}</span>
                {{ $raffle->status->name ?? 'Pendiente' }}
            </span>
        </div>
        <p class="text-body-md text-on-surface-variant mt-xs">
            ID: RFA-{{ str_pad($raffle->id, 4, '0', STR_PAD_LEFT) }} • Creado: {{ $raffle->created_at->format('d M Y') }}
        </p>
    </div>
    <div class="flex gap-md">
        <a href="{{ route('raffle.edit', $raffle) }}"
           class="px-lg py-sm rounded-lg bg-primary-container text-on-primary-container font-bold hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-shadow flex items-center gap-sm">
            <span class="material-symbols-outlined text-[20px]">edit</span>
            Editar
        </a>
        <form action="{{ route('raffle.destroy', $raffle) }}" method="POST" onsubmit="return confirm('¿Eliminar este sorteo? Esta acción no se puede deshacer.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-lg py-sm rounded-lg border border-outline text-on-surface hover:border-error hover:text-error transition-colors flex items-center gap-sm">
                <span class="material-symbols-outlined text-[20px]">delete</span>
                Eliminar
            </button>
        </form>
    </div>
</div>

{{-- Tarjetas de métricas --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md mb-xl">

    <div class="bg-surface-container border border-surface-variant p-lg rounded-xl flex flex-col justify-between h-[140px]">
        <p class="text-body-md text-on-surface-variant flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">payments</span> Ingresos estimados
        </p>
        <p class="text-stats-number text-on-surface">
            ${{ number_format(($raffle->tickets_sold_count ?? 0) * $raffle->ticket_price, 2) }}
        </p>
    </div>

    <div class="bg-surface-container border border-surface-variant p-lg rounded-xl flex flex-col justify-between h-[140px]">
        <p class="text-body-md text-on-surface-variant flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">donut_large</span> Progreso hacia el sorteo
        </p>
        <div>
            <p class="text-stats-number text-on-surface">{{ round($sold) }}%</p>
            <p class="font-mono-label text-label-caps text-on-surface-variant mt-1">Meta mínima: {{ $threshold }}%</p>
        </div>
    </div>

    <div class="bg-surface-container border border-surface-variant p-lg rounded-xl flex flex-col justify-between h-[140px]">
        <p class="text-body-md text-on-surface-variant flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">confirmation_number</span> Boletos vendidos
        </p>
        <div>
            <p class="text-stats-number text-on-surface">{{ $raffle->tickets_sold_count ?? 0 }} / {{ $raffle->ticket_count }}</p>
            <div class="w-full bg-surface-container-highest h-2 rounded-full mt-2">
                <div class="bg-primary h-2 rounded-full" style="width: {{ min($sold, 100) }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-surface-container border border-surface-variant p-lg rounded-xl flex flex-col justify-between h-[140px]">
        <p class="text-body-md text-on-surface-variant flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">calendar_month</span> Fecha del sorteo
        </p>
        <p class="text-stats-number text-on-surface" style="font-size: 22px; line-height: 28px;">
            {{ $raffle->draw_date?->format('d M Y') ?? 'Sin definir' }}
        </p>
    </div>

</div>

{{-- Configuración + Descripción --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-xl mb-xl">

    {{-- Columna izquierda: configuración --}}
    <div class="lg:col-span-1 space-y-md">
        <h3 class="text-headline-md text-on-surface">Configuración</h3>
        <div class="bg-surface-container border border-surface-variant p-lg rounded-xl space-y-md">
            <div class="flex justify-between items-center border-b border-outline-variant/50 pb-sm">
                <span class="text-body-md text-on-surface-variant">Precio del boleto</span>
                <span class="text-body-md font-bold text-primary">${{ number_format($raffle->ticket_price, 2) }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-outline-variant/50 pb-sm">
                <span class="text-body-md text-on-surface-variant">Oportunidades por boleto</span>
                <span class="text-body-md font-bold text-on-surface">{{ $raffle->opportunities }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-outline-variant/50 pb-sm">
                <span class="text-body-md text-on-surface-variant">% mínimo para sortear</span>
                <span class="text-body-md font-bold text-on-surface">{{ $threshold }}%</span>
            </div>
            <div class="flex justify-between items-center border-b border-outline-variant/50 pb-sm">
                <span class="text-body-md text-on-surface-variant">Horas de reserva</span>
                <span class="text-body-md font-bold text-on-surface">{{ $raffle->reservation_expiration_hours ?? 'Por defecto' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-body-md text-on-surface-variant">Fecha del sorteo</span>
                <span class="text-body-md font-bold text-primary">{{ $raffle->draw_date?->format('d M Y') ?? 'Sin definir' }}</span>
            </div>
        </div>
    </div>

    {{-- Columna derecha: descripción + premios --}}
    <div class="lg:col-span-2 space-y-xl">
        <div>
            <h3 class="text-headline-md text-on-surface mb-md">Descripción</h3>
            <div class="bg-surface-container border border-surface-variant rounded-xl p-lg">
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    {{ $raffle->description ?: 'Este sorteo aún no tiene descripción.' }}
                </p>
            </div>
        </div>

        <div>
            <h3 class="text-headline-md text-on-surface mb-md">Premios</h3>

            @if ($raffle->prizes->isEmpty())
                <div class="bg-surface-container border border-dashed border-outline-variant/40 rounded-xl p-8 text-center">
                    <span class="material-symbols-outlined text-3xl text-on-surface-variant/50 block mb-2">emoji_events</span>
                    <p class="text-on-surface-variant text-sm">Aún no se han agregado premios a este sorteo.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    @foreach ($raffle->prizes as $prize)
                        <div class="bg-surface-container border border-surface-variant rounded-xl overflow-hidden">
                            <div class="h-40 bg-surface-container-highest relative overflow-hidden">
                                @if ($prize->image_path)
                                    <img src="{{ asset('storage/' . $prize->image_path) }}" class="w-full h-full object-cover" alt="{{ $prize->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">image</span>
                                    </div>
                                @endif
                                <div class="absolute top-2 left-2 bg-surface/80 backdrop-blur px-2 py-1 rounded text-primary font-mono-label text-label-caps border border-primary/30">
                                    Lugar {{ $prize->position }}
                                </div>
                            </div>
                            <div class="p-md">
                                <h4 class="text-body-lg font-bold text-on-surface">{{ $prize->title }}</h4>
                                <p class="text-body-md text-on-surface-variant mt-sm">{{ $prize->description ?: 'Sin descripción.' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

{{-- Ventas recientes --}}
<div>
    <h3 class="text-headline-md text-on-surface mb-md">Ventas recientes</h3>
    <div class="bg-surface-container border border-surface-variant rounded-xl overflow-hidden">
        <div class="rounded-lg border border-dashed border-outline-variant/40 p-8 text-center">
            <span class="material-symbols-outlined text-3xl text-on-surface-variant/50 block mb-2">receipt_long</span>
            <p class="text-on-surface-variant text-sm">La lista de ventas y boletos estará disponible pronto.</p>
        </div>
    </div>
</div>

@endsection
