@extends('layouts.main')

@section('title', 'Crear Sorteo')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="mb-8 border-b border-md-outline-variant/50 pb-4">
        <a href="{{ route('raffle.index') }}" class="text-sm text-md-on-surface-variant hover:text-md-primary-container transition-colors">← Volver a sorteos</a>
        <h1 class="text-4xl font-bold text-md-on-surface tracking-tight mt-2">Crear Sorteo</h1>
        <p class="text-md-on-surface-variant mt-1">Configura un nuevo evento de rifa en el sistema.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-emerald-300 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('raffle.store') }}" method="POST">
        @include('raffle._form', ['statuses' => $statuses])
    </form>
</div>
@endsection
