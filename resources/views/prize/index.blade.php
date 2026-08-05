@extends('layouts.main')

@section('title', 'Premios del Sorteo')

@section('content')
<div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-xl">
    <div>
        <a href="{{ route('raffle.show', $raffle) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver al sorteo</a>
        <h2 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter mt-2">Premios</h2>
        <p class="text-body-md text-on-surface-variant mt-xs">{{ $raffle->name }}</p>
    </div>
    <a href="{{ route('raffle.prize.create', $raffle) }}"
       class="px-lg py-sm bg-primary text-on-primary rounded font-bold hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all text-body-md flex items-center gap-sm w-fit">
        <span class="material-symbols-outlined text-[20px]">add_circle</span>
        Agregar Premio
    </a>
</div>

@if (session('success'))
    <div class="mb-lg rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
        {{ session('success') }}
    </div>
@endif

<div class="flex flex-col gap-sm">

    <div class="hidden md:grid grid-cols-[80px_1fr_140px_100px_140px_96px] gap-md px-lg py-sm text-on-surface-variant font-mono-label text-label-caps uppercase tracking-widest border-b border-outline-variant">
        <div>Imagen</div>
        <div>Premio</div>
        <div class="text-right">Valor</div>
        <div class="text-center">Lugar</div>
        <div class="text-center">Boleto ganador</div>
        <div></div>
    </div>

    @forelse ($prizes as $prize)
        <div class="grid grid-cols-1 md:grid-cols-[80px_1fr_140px_100px_140px_96px] gap-md items-center bg-surface-container border border-surface-variant rounded-lg p-md
                    {{ $prize->position === 1 ? 'border-primary/60 shadow-[0_0_15px_rgba(245,158,11,0.15)]' : '' }}">

            <div class="w-16 h-16 rounded-md overflow-hidden border border-outline-variant bg-surface-container-lowest flex items-center justify-center">
                @if ($prize->image_path)
                    <img src="{{ asset('storage/' . $prize->image_path) }}" class="w-full h-full object-cover" alt="{{ $prize->title }}">
                @else
                    <span class="material-symbols-outlined text-on-surface-variant/40">image</span>
                @endif
            </div>

            <div class="flex flex-col justify-center">
                <p class="font-mono-label text-[10px] text-on-surface-variant/60">{{ $prize->type->name ?? 'Sin categoría' }}</p>
                <h3 class="text-body-lg font-bold text-on-surface truncate">{{ $prize->title }}</h3>
                <p class="text-on-surface-variant text-sm truncate mt-xs">{{ $prize->description ?: 'Sin descripción' }}</p>
            </div>

            <div class="text-left md:text-right text-stats-number text-lg text-on-surface">
                {{ $prize->amount ? '$' . number_format($prize->amount, 2) : '—' }}
            </div>

            <div class="flex md:justify-center">
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full border {{ $prize->position === 1 ? 'border-primary text-primary bg-primary/10' : 'border-secondary text-secondary bg-secondary/10' }} font-mono-label text-[11px]">
                    @if ($prize->position === 1)
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">trophy</span>
                    @endif
                    Lugar {{ $prize->position }}
                </span>
            </div>

            <div class="flex md:justify-center">
                @if ($prize->ticket)
                    <span class="font-mono-label text-primary tracking-widest bg-surface-container-lowest px-3 py-1.5 rounded-md border border-primary/30 text-sm">
                        {{ $prize->ticket->number }}
                    </span>
                @else
                    <span class="font-mono-label text-on-surface-variant/50 tracking-widest bg-surface-container-lowest px-3 py-1.5 rounded-md border border-outline-variant border-dashed text-sm">
                        Pendiente
                    </span>
                @endif
            </div>

            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('raffle.prize.edit', [$raffle, $prize]) }}" class="p-2 rounded text-on-surface-variant hover:text-primary hover:bg-surface-variant/30 transition-colors" title="Editar">
                    <span class="material-symbols-outlined text-xl">edit</span>
                </a>
                <form action="{{ route('raffle.prize.destroy', [$raffle, $prize]) }}" method="POST" onsubmit="return confirm('¿Eliminar este premio?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded text-on-surface-variant hover:text-error hover:bg-surface-variant/30 transition-colors" title="Eliminar">
                        <span class="material-symbols-outlined text-xl">delete</span>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-surface-container border border-dashed border-outline-variant/40 rounded-xl p-8 text-center">
            <span class="material-symbols-outlined text-3xl text-on-surface-variant/50 block mb-2">emoji_events</span>
            <p class="text-on-surface-variant text-sm mb-4">Este sorteo aún no tiene premios.</p>
            <a href="{{ route('raffle.prize.create', $raffle) }}" class="text-primary hover:underline underline-offset-4">Agrega el primero</a>
        </div>
    @endforelse
</div>
@endsection
