@extends('layouts.main')

@section('title', 'Nuevo Premio')

@section('content')
<div class="max-w-4xl mx-auto py-8 w-full">
    <div class="mb-8 border-b border-outline-variant/50 pb-4">
        <a href="{{ route('raffle.prize.index', $raffle) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver a premios</a>
        <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Nuevo Premio</h1>
        <p class="text-on-surface-variant mt-1">Sorteo: {{ $raffle->name }}</p>
    </div>

    <form action="{{ route('raffle.prize.store', $raffle) }}" method="POST" enctype="multipart/form-data">
        @include('prize._form', ['raffle' => $raffle, 'types' => $types, 'nextPosition' => $nextPosition])
    </form>
</div>
@endsection
