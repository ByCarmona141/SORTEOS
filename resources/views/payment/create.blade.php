@extends('layouts.main')

@section('title', 'Registrar Pago')

@section('content')
    <div class="max-w-3xl mx-auto py-8 w-full">
        <div class="mb-8 border-b border-outline-variant/50 pb-4">
            <a href="{{ route('payment.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver a pagos</a>
            <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Registrar Pago</h1>
            <p class="text-on-surface-variant mt-1">Úsalo para registrar pagos hechos en efectivo o fuera de línea.</p>
        </div>

        <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
            @include('payment._form', ['raffles' => $raffles, 'paymentMethods' => $paymentMethods])
        </form>
    </div>
@endsection
