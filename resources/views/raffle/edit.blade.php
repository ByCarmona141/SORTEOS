@extends('layouts.main')

@section('title', 'Editar Sorteo')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="mb-8 border-b border-md-outline-variant/50 pb-4">
        <a href="{{ route('raffle.show', $raffle) }}" class="text-sm text-md-on-surface-variant hover:text-md-primary-container transition-colors">← Volver al sorteo</a>
        <h1 class="text-4xl font-bold text-md-on-surface tracking-tight mt-2">Editar Sorteo</h1>
        <p class="text-md-on-surface-variant mt-1">{{ $raffle->name }}</p>
    </div>

    <form action="{{ route('raffle.update', $raffle) }}" method="POST">
        @method('PUT')
        @include('raffle._form', ['raffle' => $raffle, 'statuses' => $statuses])
    </form>
</div>
@endsection
