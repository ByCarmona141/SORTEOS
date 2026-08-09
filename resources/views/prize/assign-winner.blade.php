@extends('layouts.main')

@section('title', 'Asignar Boleto Ganador')

@section('content')
<div class="max-w-4xl mx-auto py-8 w-full">

    <div class="mb-8 border-b border-outline-variant/50 pb-4">
        <a href="{{ route('raffle.prize.index', $raffle) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver a premios</a>
        <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Asignar Boleto Ganador</h1>
        <p class="text-on-surface-variant mt-1">{{ $raffle->name }}</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tarjeta del premio --}}
    <div class="glass-card p-lg flex gap-md items-center mb-lg">
        <div class="w-20 h-20 rounded-lg overflow-hidden border border-outline-variant bg-surface-container-lowest flex items-center justify-center shrink-0">
            @if ($prize->image_path)
                <img src="{{ asset('storage/' . $prize->image_path) }}" class="w-full h-full object-cover" alt="{{ $prize->title }}">
            @else
                <span class="material-symbols-outlined text-on-surface-variant/40 text-3xl">image</span>
            @endif
        </div>
        <div class="flex-1">
            <span class="inline-block font-mono-label text-label-caps text-primary border border-primary/30 bg-primary/10 px-2 py-0.5 rounded uppercase mb-1">
                Lugar {{ $prize->position }}
            </span>
            <h2 class="text-xl font-bold text-on-surface">{{ $prize->title }}</h2>
            <p class="text-on-surface-variant text-sm">{{ $raffle->ticket_count }} boletos · {{ $raffle->opportunities }} oportunidad(es) por boleto</p>
        </div>
    </div>

    {{-- Ganador actual, si ya tenía uno --}}
    @if ($prize->ticket)
        <div class="mb-lg rounded-lg border border-primary/40 bg-primary/5 p-md flex items-center justify-between">
            <div>
                <p class="font-mono-label text-label-caps text-on-surface-variant">BOLETO GANADOR ACTUAL</p>
                <p class="text-2xl font-mono-label text-primary tracking-widest mt-1">{{ $prize->ticket->number }}</p>
            </div>
            <span class="material-symbols-outlined text-primary text-3xl">emoji_events</span>
        </div>
    @endif

    <form action="{{ route('raffle.prize.winner.update', [$raffle, $prize]) }}" method="POST" class="glass-card p-lg">
        @csrf
        @method('PUT')

        <label class="premium-label" for="drawn_number">Número que salió en el sorteo *</label>
        <input type="number" id="drawn_number" name="drawn_number"
               min="1" max="{{ $raffle->ticket_count * $raffle->opportunities }}"
               class="premium-input text-center tracking-[0.4em] text-2xl font-mono-label"
               placeholder="0000"
               value="{{ old('drawn_number') }}"
               autofocus required>
        @error('drawn_number')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-on-surface-variant">
            Escribe el número tal como salió en el sorteo oficial. Puede ir del 1 al {{ $raffle->ticket_count * $raffle->opportunities }}, porque cada boleto tiene {{ $raffle->opportunities }} oportunidad(es) de ganar.
        </p>

        <div id="preview-box" class="mt-lg rounded-lg border border-outline-variant bg-surface-container-lowest p-md hidden">
            <p class="font-mono-label text-label-caps text-on-surface-variant uppercase mb-2">Ese número corresponde al boleto</p>
            <p id="preview-ticket" class="text-3xl font-mono-label text-primary tracking-widest"></p>

            <p class="font-mono-label text-label-caps text-on-surface-variant uppercase mt-4 mb-2">Este boleto también pudo ganar con</p>
            <div id="preview-combos" class="flex flex-wrap gap-sm"></div>
        </div>

        <div class="flex flex-col md:flex-row justify-end items-center gap-md pt-lg mt-lg border-t border-outline-variant/30">
            <a href="{{ route('raffle.prize.index', $raffle) }}"
               class="w-full md:w-auto text-center px-lg py-sm font-bold text-on-surface border border-outline-variant rounded-lg hover:border-primary hover:text-primary transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="w-full md:w-auto px-xl py-sm font-bold bg-primary text-on-primary rounded-lg hover:shadow-[0_0_15px_rgba(255,193,116,0.4)] transition-all flex items-center justify-center gap-sm">
                <span class="material-symbols-outlined">check_circle</span>
                Confirmar Ganador
            </button>
        </div>
    </form>

    @php
        $otherPrizes = $raffle->prizes()->where('id', '!=', $prize->id)->whereNotNull('ticket_id')->with('ticket')->get();
    @endphp
    @if ($otherPrizes->isNotEmpty())
        <div class="mt-lg">
            <p class="font-mono-label text-label-caps text-on-surface-variant uppercase mb-sm">Boletos ya usados en otros premios de este sorteo</p>
            <div class="flex flex-wrap gap-sm">
                @foreach ($otherPrizes as $other)
                    <span class="px-sm py-1 rounded border border-outline-variant bg-surface-container text-on-surface-variant text-xs font-mono-label">
                        {{ $other->ticket->number }} — {{ $other->title }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    const ticketCount = {{ $raffle->ticket_count }};
    const opportunities = {{ $raffle->opportunities }};
    const numberLength = {{ $raffle->ticketNumberLength() }};

    function pad(num) {
        return String(num).padStart(numberLength, '0');
    }

    const input = document.getElementById('drawn_number');
    const box = document.getElementById('preview-box');
    const ticketOut = document.getElementById('preview-ticket');
    const combosOut = document.getElementById('preview-combos');

    input.addEventListener('input', function () {
        const value = parseInt(this.value, 10);

        if (!value || value < 1 || value > ticketCount * opportunities) {
            box.classList.add('hidden');
            return;
        }

        const physical = ((value - 1) % ticketCount) + 1;
        ticketOut.textContent = pad(physical);

        combosOut.innerHTML = '';
        for (let i = 0; i < opportunities; i++) {
            const combo = physical + (i * ticketCount);
            const isCurrent = combo === value;
            const span = document.createElement('span');
            span.textContent = pad(combo);
            span.className = 'px-sm py-1 rounded border text-xs font-mono-label ' +
                (isCurrent
                    ? 'border-primary bg-primary/10 text-primary font-bold'
                    : 'border-outline-variant bg-surface-container text-on-surface-variant');
            combosOut.appendChild(span);
        }

        box.classList.remove('hidden');
    });
</script>
@endpush
