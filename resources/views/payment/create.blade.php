@extends('layouts.main')

@section('title', 'Registrar Pago')

@section('content')
    <div class="max-w-3xl mx-auto py-8 w-full">
        <div class="mb-8 border-b border-outline-variant/50 pb-4">
            <a href="{{ route('payment.sale.tickets', $saleRaffle) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Cambiar boletos</a>
            <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Registrar Pago</h1>
            <p class="text-on-surface-variant mt-1">Completa los datos para confirmar la venta.</p>
        </div>

        @if (session('error'))
            <div class="mb-lg rounded border border-error/30 bg-error/10 px-md py-sm text-error text-body-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-surface-container border border-primary/30 rounded-lg p-lg mb-lg">
            <div class="flex justify-between items-center mb-sm">
                <p class="font-bold text-on-surface">{{ $saleRaffle->name }}</p>
                <a href="{{ route('payment.sale.tickets', $saleRaffle) }}" class="text-primary text-sm hover:underline">Cambiar boletos</a>
            </div>
            <p class="font-mono-label text-label-caps text-on-surface-variant">
                {{ $saleTickets->pluck('number')->join(', ') }}
            </p>
            <p class="text-primary font-bold mt-sm">{{ $saleTickets->count() }} boletos · ${{ number_format($saleTickets->count() * $saleRaffle->ticket_price, 2) }}</p>
        </div>

        <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
            @include('payment._form', [
                'paymentMethods' => $paymentMethods,
                'saleRaffle' => $saleRaffle,
                'saleTickets' => $saleTickets,
            ])
        </form>
    </div>
@endsection
