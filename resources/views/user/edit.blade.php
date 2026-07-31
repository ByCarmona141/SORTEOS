@extends('layouts.main')

@section('title', 'Editar Usuario')

@section('content')
<div class="max-w-3xl mx-auto py-8 w-full">
    <div class="mb-8 border-b border-outline-variant/50 pb-4">
        <a href="{{ route('user.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver a usuarios</a>
        <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Editar Usuario</h1>
        <p class="text-on-surface-variant mt-1">{{ $user->name }}</p>
    </div>

    <form action="{{ route('user.update', $user) }}" method="POST">
        @method('PUT')
        @include('user._form', ['user' => $user, 'roles' => $roles])
    </form>
</div>
@endsection