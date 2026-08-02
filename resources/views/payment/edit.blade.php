@extends('layouts.main')

@section('title', 'Editar Pago')

@section('content')
    <div class="max-w-3xl mx-auto py-8 w-full">
        <div class="mb-8 border-b border-outline-variant/50 pb-4">
            <a href="{{ route('payment.show', $payment) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver al pago</a>
            <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Editar Pago</h1>
            <p class="text-on-surface-variant mt-1">{{ $payment->user->name ?? '' }} · {{ $payment->raffle->name ?? '' }}</p>
        </div>

        <form action="{{ route('payment.update', $payment) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('payment._form', ['payment' => $payment, 'paymentMethods' => $paymentMethods])
        </form>
    </div>
@endsection
