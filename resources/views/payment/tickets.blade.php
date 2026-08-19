@extends('layouts.main')

@section('title', 'Boletos del Pago')

@section('content')
    <div class="mb-8 border-b border-outline-variant/50 pb-4">
        <a href="{{ route('payment.show', $payment) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver al pago</a>
        <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Boletos generados</h1>
        <p class="text-on-surface-variant mt-1">{{ $payment->user->name ?? 'N/D' }} · {{ $payment->raffle->name ?? 'N/D' }}</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-md">
        @forelse ($payment->tickets as $ticket)
            <div class="bg-surface-container border border-surface-variant rounded-xl p-lg flex flex-col items-center text-center gap-sm">
                <span class="material-symbols-outlined text-4xl {{ $ticket->pdf_path ? 'text-primary' : 'text-on-surface-variant/40' }}">
                    {{ $ticket->pdf_path ? 'picture_as_pdf' : 'error' }}
                </span>
                <p class="font-mono-label text-2xl tracking-widest text-on-surface">{{ $ticket->number }}</p>

                @if ($ticket->pdf_path)
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($ticket->pdf_path) }}" target="_blank"
                       class="w-full text-center px-lg py-sm bg-primary-container text-on-primary-container rounded-lg font-bold hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all">
                        Ver PDF
                    </a>
                @else
                    <p class="text-on-surface-variant text-sm">El PDF no se pudo generar. Intenta aprobar el pago de nuevo o contacta a soporte.</p>
                @endif
            </div>
        @empty
            <p class="col-span-full text-center text-on-surface-variant py-8">Este pago no tiene boletos asociados.</p>
        @endforelse
    </div>
@endsection