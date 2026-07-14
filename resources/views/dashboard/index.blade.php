@extends('layouts.main')

@section('eyebrow', 'Panel administrativo')
@section('title', 'Escritorio')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl bg-casino-darker/60 border border-white/10 p-5 hover:border-casino-gold/40 transition-colors">
            <p class="text-xs text-casino-white/40 uppercase tracking-wide">Fecha</p>
            <p class="font-display text-xl mt-1">{{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
        </div>

        <div class="rounded-xl bg-casino-darker/60 border border-white/10 p-5 hover:border-casino-gold/40 transition-colors">
            <p class="text-xs text-casino-white/40 uppercase tracking-wide">Estado del sistema</p>
            <p class="font-display text-xl mt-1 text-casino-gold">Operativo</p>
        </div>

        <div class="rounded-xl bg-casino-darker/60 border border-white/10 p-5 hover:border-casino-gold/40 transition-colors">
            <p class="text-xs text-casino-white/40 uppercase tracking-wide">Sorteos activos</p>
            <p class="font-display text-xl mt-1">—</p>
        </div>
    </div>
@endsection