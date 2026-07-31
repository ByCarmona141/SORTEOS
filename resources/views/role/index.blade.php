@extends('layouts.main')

@section('title', 'Roles y Permisos')

@section('content')
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-md motion-safe:animate-fade-slide-up">
        <div>
            <h2 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter">Roles y Permisos</h2>
            <p class="text-body-md text-on-surface-variant mt-xs">Define qué puede hacer cada tipo de usuario en el sistema.</p>
        </div>
        <a href="{{ route('role.create') }}"
           class="px-lg py-sm bg-primary text-on-primary rounded font-bold hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all text-body-md flex items-center gap-sm w-fit">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Nuevo Rol
        </a>
    </div>

    @if (session('success'))
        <div class="rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded border border-error/30 bg-error/10 px-md py-sm text-error text-body-md">{{ session('error') }}</div>
    @endif

    <div class="bg-surface-container border border-surface-variant rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-variant text-on-surface-variant font-mono-label text-label-caps uppercase">
                    <th class="text-left px-lg py-md font-medium">Rol</th>
                    <th class="text-left px-lg py-md font-medium">Permisos asignados</th>
                    <th class="text-right px-lg py-md font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-variant">
                @forelse ($roles as $role)
                    <tr class="hover:bg-surface-variant/20 transition-colors">
                        <td class="px-lg py-md font-bold text-on-surface">{{ $role->name }}</td>
                        <td class="px-lg py-md text-on-surface-variant">{{ $role->permissions_count }} permiso(s)</td>
                        <td class="px-lg py-md">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('role.edit', $role) }}" class="p-2 rounded text-on-surface-variant hover:text-primary hover:bg-surface-variant/30 transition-colors" title="Editar permisos">
                                    <span class="material-symbols-outlined text-xl">tune</span>
                                </a>
                                @if (!in_array($role->name, ['Admin', 'User']))
                                    <form action="{{ route('role.destroy', $role) }}" method="POST" onsubmit="return confirm('¿Eliminar este rol?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded text-on-surface-variant hover:text-error hover:bg-surface-variant/30 transition-colors" title="Eliminar">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-lg py-xl text-center text-on-surface-variant">Aún no hay roles creados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $roles->links() }}</div>
@endsection