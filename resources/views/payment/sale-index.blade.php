@extends('layouts.main')

@section('title', 'Vender Boletos')

@section('content')
<div class="mb-8 border-b border-outline-variant/50 pb-4">
    <a href="{{ route('payment.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver a pagos</a>
    <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Vender Boletos</h1>
    <p class="text-on-surface-variant mt-1">Elige el sorteo en el que quieres registrar una venta.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-md">
    @forelse ($raffles as $raffle)
        <a href="{{ route('payment.sale.tickets', $raffle) }}"
           class="bg-surface-container border border-surface-variant rounded-xl p-lg hover:border-primary transition-colors">
            <p class="font-bold text-on-surface text-lg">{{ $raffle->name }}</p>
            <p class="text-on-surface-variant text-sm mt-1">${{ number_format($raffle->ticket_price, 2) }} por boleto</p>
            <p class="text-primary text-sm mt-3 flex items-center gap-1">
                Seleccionar boletos <span class="material-symbols-outlined text-base">arrow_forward</span>
            </p>
        </a>
    @empty
        <div class="sm:col-span-2 lg:col-span-3 bg-surface-container border border-dashed border-outline-variant/40 rounded-xl p-8 text-center text-on-surface-variant">
            No hay sorteos activos disponibles para venta.
        </div>
    @endforelse
</div>
@endsection
