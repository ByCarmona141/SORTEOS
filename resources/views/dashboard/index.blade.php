@extends('layouts.main')

@section('title', 'Escritorio')

@section('content')
    <div class="flex justify-between items-end motion-safe:animate-fade-slide-up">
        <div>
            <h2 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter">Resumen Global</h2>
            <p class="text-body-md text-on-surface-variant mt-xs">Monitoreo en tiempo real de sorteos y rendimiento financiero.</p>
        </div>
        <div class="flex gap-md">
            <a href="{{ route('payment.index') }}" class="px-lg py-sm border border-outline text-on-surface rounded hover:border-primary hover:text-primary transition-colors text-body-md">
                Validar Pagos
            </a>
            <a href="{{ route('raffle.create') }}" class="px-lg py-sm bg-primary text-on-primary rounded font-bold hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all text-body-md">
                Crear Sorteo
            </a>
        </div>
    </div>

    {{-- KPIs: usan tu componente x-ui.stat-card, solo le agregamos ícono --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">

        <x-ui.stat-card
            label="Ingresos Validados"
            value="${{ number_format($stats['revenue'], 2) }}"
            icon="account_balance_wallet"
        />

        <x-ui.stat-card
            label="Sorteos Activos"
            value="{{ $stats['active_raffles'] }}"
            icon="celebration"
        />

        <x-ui.stat-card
            label="Boletos Vendidos"
            value="{{ number_format($stats['tickets_sold']) }}"
            icon="confirmation_number"
        />

        <x-ui.stat-card
            label="Usuarios Nuevos"
            value="{{ $stats['new_users'] }}"
            hint="últimos 30 días"
            icon="group_add"
            highlight="true"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">

        {{-- Columna izquierda: Sorteos Destacados --}}
        <div class="lg:col-span-2 flex flex-col gap-md">
            <div class="flex justify-between items-end">
                <h3 class="text-headline-md font-semibold">Sorteos Destacados</h3>
                <a href="{{ route('raffle.index') }}" class="text-sm text-primary hover:underline underline-offset-4">Ver todos</a>
            </div>

            @forelse ($raffles as $raffle)
                @php
                    $sold = $raffle->ticket_count > 0
                        ? min(100, ($raffle->tickets_sold_count / $raffle->ticket_count) * 100)
                        : 0;
                    $isHot = $sold >= 80;
                    $image = $raffle->prizes->first()->image_path ?? null;
                @endphp

                <div class="relative overflow-hidden rounded-xl border p-md
                            {{ $isHot ? 'border-primary/30 bg-surface-container-high shadow-[0_0_15px_rgba(255,193,116,0.1)]' : 'border-surface-variant bg-surface-container-high' }}">

                    @if ($isHot)
                        <div class="absolute top-0 right-0 px-3 py-1 bg-primary text-on-primary font-mono-label text-[10px] rounded-bl-lg font-bold">
                            ALTA DEMANDA
                        </div>
                    @endif

                    <div class="flex gap-md">
                        <div class="w-24 h-24 rounded-lg bg-surface-container-highest overflow-hidden shrink-0 border border-outline-variant flex items-center justify-center">
                            @if ($image)
                                <img src="{{ asset('storage/' . $image) }}" class="w-full h-full object-cover" alt="{{ $raffle->name }}">
                            @else
                                <span class="material-symbols-outlined text-3xl text-on-surface-variant/40">confirmation_number</span>
                            @endif
                        </div>

                        <div class="flex-1 flex flex-col justify-between min-w-0">
                            <div>
                                <a href="{{ route('raffle.show', $raffle) }}" class="font-bold text-on-surface text-lg hover:text-primary transition-colors truncate block">
                                    {{ $raffle->name }}
                                </a>
                                <p class="text-sm text-on-surface-variant">{{ $raffle->status->name ?? 'Pendiente' }}</p>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="{{ $isHot ? 'text-primary font-bold' : 'text-on-surface' }}">{{ round($sold) }}% Vendido</span>
                                    <span class="font-mono-label text-label-caps text-on-surface-variant">
                                        {{ $raffle->tickets_sold_count }} / {{ $raffle->ticket_count }}
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-surface-container-lowest rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $isHot ? 'bg-primary' : 'bg-outline' }}" style="width: {{ $sold }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-surface-container border border-dashed border-outline-variant/40 rounded-xl p-8 text-center">
                    <span class="material-symbols-outlined text-3xl text-on-surface-variant/50 block mb-2">confirmation_number</span>
                    <p class="text-on-surface-variant text-sm mb-2">Aún no hay sorteos creados.</p>
                    <a href="{{ route('raffle.create') }}" class="text-primary hover:underline underline-offset-4">Crea el primero</a>
                </div>
            @endforelse
        </div>

        {{-- Columna derecha: Actividad Reciente --}}
        <div class="bg-surface-container border border-surface-variant rounded-xl flex flex-col">
            <div class="p-md border-b border-outline-variant/30">
                <h3 class="text-headline-md font-semibold text-lg">Actividad Reciente</h3>
            </div>

            <div class="flex-1 p-md space-y-lg overflow-y-auto max-h-[420px]">
                @forelse ($recentPayments as $payment)
                    @php $badge = $payment->statusBadge(); @endphp
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[16px] text-primary">{{ $badge['icon'] }}</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm text-on-surface">
                                <span class="font-bold">{{ $payment->user->name ?? 'Cliente eliminado' }}</span>
                                registró un pago de ${{ number_format($payment->total_amount, 2) }}
                            </p>
                            <p class="text-xs text-on-surface-variant truncate">{{ $payment->raffle->name ?? 'Sorteo eliminado' }}</p>
                            <p class="text-xs font-mono-label text-on-surface-variant/70 mt-1">{{ $payment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-body-md text-on-surface-variant">
                        Aquí aparecerán los eventos del sistema (pagos, sorteos creados, etc.) en cuanto haya actividad.
                    </p>
                @endforelse
            </div>

            <div class="p-3 border-t border-outline-variant/30 text-center">
                <a href="{{ route('payment.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">Ver todos los pagos</a>
            </div>
        </div>
    </div>
@endsection
