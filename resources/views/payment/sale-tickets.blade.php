@extends('layouts.main')

@section('title', 'Seleccionar Boletos')

@section('content')
<div class="mb-6 border-b border-outline-variant/50 pb-4">
    <a href="{{ route('payment.sale.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Elegir otro sorteo</a>
    <h1 class="text-3xl font-bold text-on-surface tracking-tight mt-2">{{ $raffle->name }}</h1>
    <p class="text-on-surface-variant mt-1">Selecciona los boletos que quieres vender. Solo los boletos en verde están disponibles.</p>
</div>

@if (session('error'))
    <div class="mb-md rounded border border-error/30 bg-error/10 px-md py-sm text-error text-body-md">
        {{ session('error') }}
    </div>
@endif

<form action="{{ route('payment.sale.store', $raffle) }}" method="POST" id="selection-form">
    @csrf

    {{-- Barra de herramientas: buscar + selección aleatoria --}}
    <div class="flex flex-wrap items-center gap-sm mb-lg bg-surface-container border border-surface-variant rounded-lg p-md">
        <input type="text" id="search-box" placeholder="Buscar boleto..." value="{{ request('search') }}"
               class="px-md py-sm bg-surface border border-outline-variant rounded text-on-surface flex-1 min-w-[160px]">

        <div class="flex items-center gap-sm">
            <input type="number" id="random-qty" min="1" placeholder="Cant."
                   class="w-20 px-md py-sm bg-surface border border-outline-variant rounded text-on-surface">
            <button type="button" id="random-select-btn"
                    class="px-md py-sm border border-outline text-on-surface rounded hover:border-primary hover:text-primary transition-colors">
                Elegir al azar
            </button>
        </div>

        <button type="button" id="clear-selection-btn"
                class="px-md py-sm text-on-surface-variant hover:text-error transition-colors">
            Limpiar selección
        </button>

        <div class="ml-auto font-mono-label text-label-caps text-primary">
            <span id="selected-count">0</span> boletos seleccionados
        </div>
    </div>

    {{-- Cuadrícula de boletos --}}
    <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 lg:grid-cols-12 gap-2 mb-xl" id="ticket-grid">
        @foreach ($tickets as $ticket)
            @php
                $status = strtolower($ticket->statusTicket->name ?? '');
                $isAvailable = $status === 'disponible';
                $colors = match ($status) {
                    'disponible' => 'bg-emerald-400/10 border-emerald-400/40 text-emerald-400 cursor-pointer hover:bg-emerald-400/20',
                    'apartado'   => 'bg-primary/10 border-primary/30 text-primary/60 cursor-not-allowed opacity-60',
                    'pagado'     => 'bg-on-surface-variant/10 border-outline-variant text-on-surface-variant/50 cursor-not-allowed opacity-60',
                    default      => 'bg-surface border-outline-variant text-on-surface-variant cursor-not-allowed opacity-60',
                };
            @endphp
            <label class="ticket-item {{ $colors }} border rounded text-center py-2 text-xs font-mono-label select-none transition-colors {{ $isAvailable ? '' : 'pointer-events-none' }}"
                   data-number="{{ $ticket->number }}">
                @if ($isAvailable)
                    <input type="checkbox" name="ticket_ids[]" value="{{ $ticket->id }}" class="hidden ticket-checkbox">
                @endif
                {{ $ticket->number }}
            </label>
        @endforeach
    </div>

    <div>{{ $tickets->links() }}</div>

    <div class="fixed bottom-0 left-0 md:left-[280px] right-0 bg-surface-container-lowest border-t border-outline-variant p-lg flex justify-end gap-md">
        <a href="{{ route('payment.sale.index') }}" class="px-lg py-sm border border-outline-variant text-on-surface rounded-lg hover:border-primary transition-colors">
            Cancelar
        </a>
        <button type="submit" class="px-xl py-sm bg-primary text-on-primary font-bold rounded-lg hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all">
            Continuar con el pago
        </button>
    </div>
</form>

@push('scripts')
<script>
    const grid = document.getElementById('ticket-grid');
    const counter = document.getElementById('selected-count');

    function updateSelectionStyles() {
        let count = 0;
        grid.querySelectorAll('.ticket-checkbox').forEach(cb => {
            const label = cb.closest('.ticket-item');
            if (cb.checked) {
                label.classList.add('ring-2', 'ring-primary', 'bg-primary/30');
                count++;
            } else {
                label.classList.remove('ring-2', 'ring-primary', 'bg-primary/30');
            }
        });
        counter.textContent = count;
    }

    grid.addEventListener('change', (e) => {
        if (e.target.classList.contains('ticket-checkbox')) {
            updateSelectionStyles();
        }
    });

    document.getElementById('random-select-btn').addEventListener('click', () => {
        const qty = parseInt(document.getElementById('random-qty').value || '0', 10);
        if (qty <= 0) return;

        const available = Array.from(grid.querySelectorAll('.ticket-checkbox:not(:checked)'));
        // Barajamos y tomamos los primeros N
        for (let i = available.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [available[i], available[j]] = [available[j], available[i]];
        }
        available.slice(0, qty).forEach(cb => cb.checked = true);
        updateSelectionStyles();
    });

    document.getElementById('clear-selection-btn').addEventListener('click', () => {
        grid.querySelectorAll('.ticket-checkbox').forEach(cb => cb.checked = false);
        updateSelectionStyles();
    });

    document.getElementById('search-box').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const url = new URL(window.location.href);
            url.searchParams.set('search', e.target.value);
            window.location.href = url.toString();
        }
    });

    document.getElementById('selection-form').addEventListener('submit', (e) => {
        if (grid.querySelectorAll('.ticket-checkbox:checked').length === 0) {
            e.preventDefault();
            alert('Selecciona al menos un boleto.');
        }
    });
</script>
@endpush
@endsection
