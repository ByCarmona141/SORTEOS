@extends('layouts.main')

@section('title', 'Pagos')

@section('content')
@php
    $badgeMap = \App\Models\Payment::statusBadgeMap();
@endphp

<div class="flex flex-col md:flex-row md:items-end justify-between gap-md motion-safe:animate-fade-slide-up">
    <div>
        <h2 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter">
            {{ $canReview ? 'Aprobación de Pagos' : 'Mis Pagos' }}
        </h2>
        <p class="text-body-md text-on-surface-variant mt-xs">
            {{ $canReview
                ? 'Revisa y valida los comprobantes de pago que suben los clientes.'
                : 'Aquí puedes ver el estado de tus pagos.' }}
        </p>
    </div>
    @if ($canReview)
        <a href="{{ route('payment.create') }}" class="px-lg py-sm bg-primary text-on-primary rounded font-bold hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all text-body-md w-fit">
            Registrar Pago
        </a>
    @endif
</div>

@if (session('success'))
    <div class="rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
        {{ session('success') }}
    </div>
@endif

@if ($canReview)
    {{-- Tarjetas de resumen por estado, igual patrón que en Sorteos --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        @foreach ($statusPayments as $status)
            @php $badge = $badgeMap[strtolower($status->name)] ?? ['icon' => 'help']; @endphp
            <x-ui.stat-card
                :label="$status->name"
                :value="$status->payments_count"
                :icon="$badge['icon']"
                :highlight="strtolower($status->name) === 'pendiente'"
            />
        @endforeach
    </div>
@endif

{{-- Filtros --}}
<form method="GET" class="flex flex-wrap items-center gap-sm">
    @if ($canReview)
        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por cliente..."
                   class="w-full pl-10 pr-4 py-sm bg-surface border border-outline-variant rounded text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
        </div>
    @endif
    <select name="status" class="px-md py-sm bg-surface border border-outline-variant rounded text-on-surface-variant text-sm focus:border-primary focus:ring-0">
        <option value="">Todos los estados</option>
        @foreach ($statusPayments as $status)
            <option value="{{ $status->name }}" @selected(request('status') === $status->name)>{{ $status->name }}</option>
        @endforeach
    </select>
    <x-ui.per-page-select />
    <button type="submit" class="px-lg py-sm border border-outline text-on-surface rounded hover:border-primary hover:text-primary transition-colors text-body-md">
        Filtrar
    </button>
    @if (request('search') || request('status'))
        <a href="{{ route('payment.index') }}" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">Limpiar</a>
    @endif
</form>

{{-- Listado de pagos (tarjetas, igual estilo al mockup que te gustó) --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-lg">
    @forelse ($payments as $payment)
        @php
            $badge = $payment->statusBadge();
            $status = strtolower($payment->statusPayment->name ?? 'pendiente');
            $barColor = $status === 'pendiente' ? 'bg-primary' : ($status === 'validado' ? 'bg-emerald-400' : 'bg-error');
        @endphp

        <div class="bg-surface-container border border-surface-variant rounded-xl p-lg flex flex-col sm:flex-row gap-lg relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $barColor }}"></div>

            <div class="flex-1">
                <div class="flex items-center justify-between mb-md gap-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full border border-outline-variant bg-surface-container-lowest flex items-center justify-center text-on-surface font-bold text-xs shrink-0">
                            {{ strtoupper(substr($payment->user->name ?? '?', 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-bold text-on-surface">{{ $payment->user->name ?? 'Cliente eliminado' }}</p>
                            <p class="font-mono-label text-label-caps text-on-surface-variant">{{ $payment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-stats-number text-[24px] text-primary">${{ number_format($payment->total_amount, 2) }}</p>
                        <span class="inline-block px-2 py-1 rounded bg-surface-container-high border border-outline-variant font-mono-label text-label-caps text-on-surface mt-1">
                            {{ $payment->paymentMethod->name ?? 'N/D' }}
                        </span>
                    </div>
                </div>

                <div class="bg-surface-container-lowest p-md rounded-lg border border-outline-variant mb-md flex justify-between items-center gap-md">
                    <div>
                        <p class="font-mono-label text-label-caps text-on-surface-variant mb-1">SORTEO</p>
                        <p class="font-mono-label text-label-caps text-on-surface">{{ $payment->raffle->name ?? 'N/D' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-mono-label text-label-caps text-on-surface-variant mb-1">REFERENCIA</p>
                        <p class="font-mono-label text-label-caps text-on-surface">{{ $payment->reference ?: '—' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-md">
                    <span class="inline-flex items-center gap-1.5 border px-2 py-1 rounded font-mono-label text-label-caps {{ $badge['classes'] }}">
                        <span class="material-symbols-outlined text-sm">{{ $badge['icon'] }}</span>
                        {{ $payment->statusPayment->name ?? 'Pendiente' }}
                    </span>
                    <button type="button" class="flex-1 bg-transparent text-on-surface border border-outline-variant hover:border-primary py-2 px-4 rounded-lg font-bold flex justify-center items-center gap-2 transition-colors"
                            onclick="document.getElementById('receipt-modal-{{ $payment->id }}').showModal()">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                        Ver Comprobante
                    </button>
                </div>
            </div>

            @if ($canReview)
                <div class="flex flex-col gap-3 justify-center sm:w-40 border-t sm:border-t-0 sm:border-l border-surface-variant pt-4 sm:pt-0 sm:pl-lg">
                    @if ($status !== 'validado')
                        <form action="{{ route('payment.approve', $payment) }}" method="POST">
                            @csrf
                            <button class="w-full bg-primary text-on-primary font-bold py-3 rounded-lg hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all flex justify-center items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                Aprobar
                            </button>
                        </form>
                    @endif

                    @if ($status !== 'rechazado')
                        <form action="{{ route('payment.reject', $payment) }}" method="POST" onsubmit="return confirm('¿Rechazar este pago? Los boletos quedarán disponibles de nuevo.');">
                            @csrf
                            <button class="w-full bg-transparent text-on-surface border border-outline-variant hover:border-error hover:text-error py-3 rounded-lg font-bold flex justify-center items-center gap-2 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">cancel</span>
                                Rechazar
                            </button>
                        </form>
                    @endif

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

        {{-- Panel lateral con el comprobante (usa <dialog> nativo del navegador) --}}
        <dialog id="receipt-modal-{{ $payment->id }}" class="receipt-dialog bg-surface-container border-l border-surface-variant p-0 m-0 ml-auto h-full max-h-full w-full" style="max-width: 28rem;">
            <div class="h-full flex flex-col">
                <div class="p-lg border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                    <h3 class="text-headline-md text-on-surface">Comprobante de Pago</h3>
                    <button type="button" class="text-on-surface-variant hover:text-primary transition-colors"
                            onclick="document.getElementById('receipt-modal-{{ $payment->id }}').close()">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-lg flex flex-col gap-lg">
                    <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant">
                        @if ($payment->proof_image && $payment->proofIsPdf())
                            <iframe src="{{ \Illuminate\Support\Facades\Storage::url($payment->proof_image) }}"
                                    class="w-full rounded-lg border-0"
                                    style="height: 400px;">
                            </iframe>
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($payment->proof_image) }}" target="_blank"
                               class="mt-3 block text-center text-primary text-sm underline">
                                Abrir PDF en otra pestaña
                            </a>
                        @elseif ($payment->proof_image)
                            <img class="w-full h-auto rounded-lg object-contain"
                                 src="{{ \Illuminate\Support\Facades\Storage::url($payment->proof_image) }}"
                                 alt="Comprobante">
                        @else
                            <p class="text-on-surface-variant text-center py-8">Sin comprobante adjunto.</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-md bg-surface-container-lowest p-md rounded-lg border border-outline-variant">
                        <div>
                            <p class="font-mono-label text-label-caps text-on-surface-variant mb-1">Monto</p>
                            <p class="text-body-lg text-primary font-bold">${{ number_format($payment->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="font-mono-label text-label-caps text-on-surface-variant mb-1">Fecha</p>
                            <p class="text-body-md text-on-surface">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-lg border-t border-outline-variant bg-surface-container-lowest">
                    <a href="{{ route('payment.show', $payment) }}" class="block text-center w-full bg-primary text-on-primary font-bold py-3 rounded-lg hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all">
                        Ver detalle completo
                    </a>
                </div>
            </div>
        </dialog>
    @empty
        <div class="xl:col-span-2 bg-surface-container border border-dashed border-outline-variant/40 rounded-xl p-8 text-center">
            <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">payments</span>
            <p class="text-on-surface-variant text-sm">
                {{ $canReview ? 'No hay pagos que coincidan con el filtro.' : 'Aún no tienes pagos registrados.' }}
            </p>
        </div>
    @endforelse
</div>

<div>{{ $payments->links() }}</div>
@endsection
