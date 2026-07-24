@extends('layouts.main')

@section('title', $raffle->name)

@section('content')
@php
    $statusStyles = [
        'draft'    => ['bg-slate-500/15 text-slate-300 border-slate-400/30', 'draft'],
        'active'   => ['bg-emerald-500/15 text-emerald-300 border-emerald-400/30', 'check_circle'],
        'finished' => ['bg-md-primary-container/15 text-md-primary border-md-primary-container/30', 'flag'],
    ];
    $statusLabels = [
        'draft'    => 'En preparación',
        'active'   => 'Activo',
        'finished' => 'Finalizado',
    ];
    $statusKey = $raffle->status->name ?? 'draft';
    [$statusClass, $statusIcon] = $statusStyles[$statusKey] ?? $statusStyles['draft'];
    $sold = $raffle->tickets_sold_percentage ?? 0;
@endphp
<div class="max-w-6xl mx-auto py-8">

    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-8 border-b border-md-outline-variant/50 pb-4">
        <div>
            <a href="{{ route('raffle.index') }}" class="text-sm text-md-on-surface-variant hover:text-md-primary-container transition-colors">← Volver a sorteos</a>
            <div class="flex flex-wrap items-center gap-3 mt-2">
                <h1 class="text-4xl font-bold text-md-on-surface tracking-tight">{{ $raffle->name }}</h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                    <span class="material-symbols-outlined text-sm">{{ $statusIcon }}</span>
                    {{ $statusLabels[$statusKey] ?? 'En preparación' }}
                </span>
            </div>
        </div>
        <a href="{{ route('raffle.edit', $raffle) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 font-bold text-md-on-surface border border-md-outline-variant/50 rounded-lg hover:border-md-primary-container hover:text-md-primary-container transition-colors">
            <span class="material-symbols-outlined text-xl">edit</span>
            Editar
        </a>
    </div>

    {{-- Tarjetas de métricas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="glass-card p-5">
            <div class="flex items-center gap-2 text-md-on-surface-variant text-xs uppercase tracking-wider mb-2">
                <span class="material-symbols-outlined text-base">confirmation_number</span>
                Boletos vendidos
            </div>
            <p class="text-2xl font-bold text-md-on-surface">{{ $raffle->tickets_sold_count ?? 0 }} / {{ $raffle->ticket_count }}</p>
        </div>
        <div class="glass-card p-5">
            <div class="flex items-center gap-2 text-md-on-surface-variant text-xs uppercase tracking-wider mb-2">
                <span class="material-symbols-outlined text-base">percent</span>
                Progreso
            </div>
            <p class="text-2xl font-bold text-md-on-surface">{{ round($sold) }}%</p>
        </div>
        <div class="glass-card p-5">
            <div class="flex items-center gap-2 text-md-on-surface-variant text-xs uppercase tracking-wider mb-2">
                <span class="material-symbols-outlined text-base">payments</span>
                Ingresos estimados
            </div>
            <p class="text-2xl font-bold text-md-on-surface">${{ number_format(($raffle->tickets_sold_count ?? 0) * $raffle->ticket_price, 2) }}</p>
        </div>
        <div class="glass-card p-5">
            <div class="flex items-center gap-2 text-md-on-surface-variant text-xs uppercase tracking-wider mb-2">
                <span class="material-symbols-outlined text-base">calendar_month</span>
                Fecha del sorteo
            </div>
            <p class="text-2xl font-bold text-md-on-surface">{{ $raffle->draw_date?->format('d/m/Y') ?? 'Sin definir' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Columna principal --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-md-primary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-md-primary-container">info</span>
                    Descripción
                </h2>
                <p class="text-md-on-surface/80 text-sm leading-relaxed">
                    {{ $raffle->description ?: 'Este sorteo aún no tiene descripción.' }}
                </p>
            </div>

            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-md-primary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-md-primary-container">confirmation_number</span>
                    Boletos
                </h2>
                <div class="rounded-lg border border-dashed border-md-outline-variant/40 p-8 text-center">
                    <span class="material-symbols-outlined text-3xl text-md-on-surface-variant/50 block mb-2">hourglass_empty</span>
                    <p class="text-md-on-surface-variant text-sm">La gestión de boletos estará disponible pronto.</p>
                </div>
            </div>
        </div>

        {{-- Columna lateral --}}
        <div class="glass-card p-6 h-fit">
            <h2 class="text-xl font-bold text-md-primary mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-md-primary-container">settings</span>
                Configuración
            </h2>
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between items-center">
                    <dt class="text-md-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">attach_money</span> Precio por boleto
                    </dt>
                    <dd class="text-md-on-surface font-medium">${{ number_format($raffle->ticket_price, 2) }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-md-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">tag</span> Total de boletos
                    </dt>
                    <dd class="text-md-on-surface font-medium">{{ $raffle->ticket_count }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-md-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">repeat</span> Oportunidades
                    </dt>
                    <dd class="text-md-on-surface font-medium">{{ $raffle->opportunities }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-md-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">hourglass_empty</span> Horas de reserva
                    </dt>
                    <dd class="text-md-on-surface font-medium">{{ $raffle->reservation_expiration_hours ?? 'Por defecto' }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-md-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">percent</span> % para sortear
                    </dt>
                    <dd class="text-md-on-surface font-medium">{{ $raffle->draw_trigger_percentage ?? 'Por defecto' }}%</dd>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-md-outline-variant/20">
                    <dt class="text-md-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">person</span> Creado por
                    </dt>
                    <dd class="text-md-on-surface font-medium">{{ $raffle->creator->name ?? 'Desconocido' }}</dd>
                </div>
            </dl>
        </div>

    </div>
</div>
@endsection
