@extends('layouts.main')

@section('title', 'Boletos - ' . $raffle->name)

@section('content')
@php
    $badgeMap = \App\Models\Ticket::statusBadgeMap();
@endphp

<div class="mb-xl border-b border-outline-variant/50 pb-4">
    <a href="{{ route('raffle.show', $raffle) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver al sorteo</a>
    <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Boletos</h1>
    <p class="text-on-surface-variant mt-1">{{ $raffle->name }} · {{ $raffle->ticket_count }} boletos en total</p>
</div>

@if (session('success'))
    <div class="mb-lg rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-lg rounded border border-error/30 bg-error/10 px-md py-sm text-error text-body-md">
        {{ session('error') }}
    </div>
@endif

@if ($raffle->tickets()->doesntExist())
    {{-- Si el sorteo todavía no tiene boletos, mostramos solo el botón para generarlos --}}
    <div class="bg-surface-container border border-dashed border-outline-variant/40 rounded-xl p-12 text-center">
        <span class="material-symbols-outlined text-4xl block mb-3 opacity-40">confirmation_number</span>
        <p class="text-on-surface-variant mb-4">Este sorteo aún no tiene boletos generados.</p>
        <form action="{{ route('raffle.tickets.generate', $raffle) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-lg py-sm bg-primary text-on-primary rounded font-bold hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all">
                Generar {{ $raffle->ticket_count }} boletos
            </button>
        </form>
    </div>
@else
    {{-- Tarjetas de resumen por estado --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-gutter mb-lg">
        @foreach ($statusTickets as $statusTicket)
            <x-ui.stat-card
                :label="$statusTicket->name"
                :value="$counts[$statusTicket->name] ?? 0"
            />
        @endforeach
    </div>

    {{-- Buscador y filtros --}}
    <form method="GET" class="glass-card bg-surface-container border border-surface-variant rounded-lg p-md mb-lg flex flex-col md:flex-row gap-sm items-center">
        <div class="relative w-full md:w-72">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar número (ej. 0042)"
                   class="w-full pl-10 pr-4 py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
        </div>

        <select name="status" class="px-md py-sm bg-surface border border-outline-variant rounded text-on-surface-variant text-sm focus:border-primary focus:ring-0">
            <option value="">Todos los estados</option>
            @foreach ($statusTickets as $statusTicket)
                <option value="{{ $statusTicket->name }}" @selected(request('status') === $statusTicket->name)>
                    {{ $statusTicket->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="px-lg py-sm border border-outline text-on-surface rounded hover:border-primary hover:text-primary transition-colors">
            Filtrar
        </button>
        @if (request('search') || request('status'))
            <a href="{{ route('raffle.tickets.index', $raffle) }}" class="text-on-surface-variant hover:text-primary transition-colors">Limpiar</a>
        @endif

        {{-- Leyenda de colores --}}
        <div class="flex gap-md items-center ml-auto flex-wrap">
            @foreach ($badgeMap as $name => $badge)
                <div class="flex items-center gap-1.5">
                    <div class="w-2.5 h-2.5 rounded-full {{ $badge['dot'] }}"></div>
                    <span class="text-[10px] font-mono-label uppercase text-on-surface-variant">{{ $name }}</span>
                </div>
            @endforeach
        </div>
    </form>

    {{-- Cuadrícula de boletos --}}
    <div class="bg-surface-container border border-surface-variant rounded-xl p-lg">
        <div class="grid gap-2" style="grid-template-columns: repeat(auto-fill, minmax(64px, 1fr));">
            @forelse ($tickets as $ticket)
                @php $badge = $ticket->statusBadge(); @endphp
                <div class="h-10 flex items-center justify-center rounded border font-mono-label text-[11px] {{ $badge['classes'] }}"
                     title="{{ $ticket->statusTicket->name ?? 'Disponible' }}">
                    {{ $ticket->number }}
                </div>
            @empty
                <p class="col-span-full text-center text-on-surface-variant py-8">No hay boletos que coincidan con el filtro.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-lg">
        {{ $tickets->links() }}
    </div>
@endif

@endsection