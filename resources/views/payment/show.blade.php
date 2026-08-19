@extends('layouts.main')

@section('title', 'Detalle de Pago')

@section('content')
    @php
        $badge = $payment->statusBadge();
        $status = strtolower($payment->statusPayment->name ?? 'pendiente');
    @endphp

    <div class="mb-8 border-b border-outline-variant/50 pb-4">
        <a href="{{ route('payment.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver a pagos</a>
        <div class="flex flex-wrap items-center gap-md mt-2">
            <h1 class="text-4xl font-bold text-on-surface tracking-tight">Pago #{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <span class="inline-flex items-center gap-1.5 border px-2 py-1 rounded font-mono-label text-label-caps {{ $badge['classes'] }}">
            <span class="material-symbols-outlined text-sm">{{ $badge['icon'] }}</span>
            {{ $payment->statusPayment->name ?? 'Pendiente' }}
        </span>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-xl">
        <div class="lg:col-span-1 space-y-md">
            <h3 class="text-headline-md text-on-surface">Información</h3>
            <div class="bg-surface-container border border-surface-variant rounded-xl p-lg space-y-md">
                <div class="flex justify-between border-b border-outline-variant/50 pb-sm">
                    <span class="text-on-surface-variant">Cliente</span>
                    <span class="font-bold text-on-surface">{{ $payment->user->name ?? 'N/D' }}</span>
                </div>
                <div class="flex justify-between border-b border-outline-variant/50 pb-sm">
                    <span class="text-on-surface-variant">Sorteo</span>
                    <span class="font-bold text-on-surface">{{ $payment->raffle->name ?? 'N/D' }}</span>
                </div>
                <div class="flex justify-between border-b border-outline-variant/50 pb-sm">
                    <span class="text-on-surface-variant">Monto</span>
                    <span class="font-bold text-primary">${{ number_format($payment->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between border-b border-outline-variant/50 pb-sm">
                    <span class="text-on-surface-variant">Método</span>
                    <span class="font-bold text-on-surface">{{ $payment->paymentMethod->name ?? 'N/D' }}</span>
                </div>
                <div class="flex justify-between border-b border-outline-variant/50 pb-sm">
                    <span class="text-on-surface-variant">Referencia</span>
                    <span class="font-bold text-on-surface">{{ $payment->reference ?: '—' }}</span>
                </div>
                <div class="flex justify-between {{ $payment->validated_at ? 'border-b border-outline-variant/50 pb-sm' : '' }}">
                    <span class="text-on-surface-variant">Fecha de pago</span>
                    <span class="font-bold text-on-surface">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if ($payment->validated_at)
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Revisado por</span>
                        <span class="font-bold text-on-surface">{{ $payment->validator->name ?? 'N/D' }} · {{ $payment->validated_at->format('d M, H:i') }}</span>
                    </div>
                @endif
            </div>

            @if ($payment->tickets->isNotEmpty())
                <div class="bg-surface-container border border-surface-variant rounded-xl p-lg">
                    <p class="font-mono-label text-label-caps text-on-surface-variant mb-2">BOLETOS INCLUIDOS</p>
                    <p class="text-on-surface mb-md">{{ $payment->tickets->pluck('number')->join(', ') }}</p>

                    <a href="{{ route('payment.tickets', $payment) }}"
                    class="w-full text-center px-lg py-sm bg-primary-container text-on-primary-container rounded-lg font-bold hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all flex items-center justify-center gap-sm">
                        <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                        Ver boletos y PDF
                    </a>
                </div>
            @endif

            @if ($canReview)
                <div class="flex gap-md">
                    <a href="{{ route('payment.edit', $payment) }}" class="flex-1 text-center px-lg py-sm rounded-lg bg-primary-container text-on-primary-container font-bold hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-shadow">
                        Editar
                    </a>
                    <form action="{{ route('payment.destroy', $payment) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar este pago? No se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-lg py-sm rounded-lg border border-outline text-on-surface hover:border-error hover:text-error transition-colors">
                            Eliminar
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 space-y-lg">
            <h3 class="text-headline-md text-on-surface">Comprobante</h3>
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
                @if ($payment->proof_image && $payment->proofIsPdf())
                    <iframe src="{{ \Illuminate\Support\Facades\Storage::url($payment->proof_image) }}"
                            class="w-full rounded-lg border-0"
                            style="height: 600px;">
                    </iframe>
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($payment->proof_image) }}" target="_blank"
                       class="mt-3 block text-center text-primary text-sm underline">
                        Abrir PDF en otra pestaña
                    </a>
                @elseif ($payment->proof_image)
                    <img class="w-full mx-auto h-auto rounded-lg object-contain"
                        src="{{ \Illuminate\Support\Facades\Storage::url($payment->proof_image) }}"
                        alt="Comprobante"
                        style="max-width: 32rem;">
                @else
                    <p class="text-on-surface-variant text-center py-12">Sin comprobante adjunto.</p>
                @endif
            </div>

            @if ($canReview)
                <div class="flex flex-col gap-md">
                    <div class="flex gap-md">
                        @if ($status !== 'validado')
                            <form action="{{ route('payment.approve', $payment) }}" method="POST" class="flex-1">
                                @csrf
                                <button class="w-full bg-primary text-on-primary font-bold py-3 rounded-lg hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all flex justify-center items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                    Aprobar Pago
                                </button>
                            </form>
                        @endif
                        @if ($status !== 'rechazado')
                            <form action="{{ route('payment.reject', $payment) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Rechazar este pago?');">
                                @csrf
                                <button class="w-full bg-transparent text-on-surface border border-outline-variant hover:border-error hover:text-error py-3 rounded-lg font-bold flex justify-center items-center gap-2 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">cancel</span>
                                    Rechazar Pago
                                </button>
                            </form>
                        @endif
                    </div>

                    @if ($status !== 'pendiente')
                        <form action="{{ route('payment.revert', $payment) }}" method="POST" onsubmit="return confirm('¿Volver este pago a Pendiente?');">
                            @csrf
                            <button class="w-full bg-transparent text-on-surface-variant border border-outline-variant/50 hover:border-primary hover:text-primary py-2 rounded-lg text-sm flex justify-center items-center gap-2 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">undo</span>
                                Revertir a Pendiente
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
