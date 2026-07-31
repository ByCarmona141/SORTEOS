@extends('layouts.main')

@section('title', 'Nuevo Rol')

@section('content')
<div class="max-w-4xl mx-auto py-8 w-full">
    <div class="mb-8 border-b border-outline-variant/50 pb-4">
        <a href="{{ route('role.index') }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">← Volver a roles</a>
        <h1 class="text-4xl font-bold text-on-surface tracking-tight mt-2">Nuevo Rol</h1>
        <p class="text-on-surface-variant mt-1">Define un rol y marca los permisos que tendrá.</p>
    </div>

    <form action="{{ route('role.store') }}" method="POST">
        @include('role._form', ['permissions' => $permissions])
    </form>
</div>
@endsection