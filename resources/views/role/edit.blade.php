@extends('layouts.main')

@section('title', 'Editar Rol')

@section('content')
<div class="max-w-4xl mx-auto py-8 w-full">
    <div class="mb-8 border-b border-outline-variant/50 pb-4">
        <a href="{{ route('role.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver a roles</a>
        <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Editar Rol</h1>
        <p class="text-on-surface-variant mt-1">{{ $role->name }}</p>
    </div>

    <form action="{{ route('role.update', $role) }}" method="POST">
        @method('PUT')
        @include('role._form', ['role' => $role, 'permissions' => $permissions])
    </form>
</div>
@endsection