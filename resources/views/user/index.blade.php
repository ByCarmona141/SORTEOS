@extends('layouts.main')

@section('title', 'Usuarios')

@section('content')
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-md motion-safe:animate-fade-slide-up">
        <div>
            <h2 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter">Gestión de Usuarios</h2>
            <p class="text-body-md text-on-surface-variant mt-xs">Administra el acceso, roles y estado de los participantes del sistema.</p>
        </div>
        <a href="{{ route('user.create') }}"
           class="px-lg py-sm bg-primary text-on-primary rounded font-bold hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all text-body-md flex items-center gap-sm w-fit">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Nuevo Usuario
        </a>
    </div>

    @if (session('success'))
        <div class="rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded border border-error/30 bg-error/10 px-md py-sm text-error text-body-md">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filtros --}}
    <form method="GET" class="flex flex-wrap items-center gap-sm">
        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por nombre o email..."
                   class="w-full pl-10 pr-4 py-sm bg-surface border border-outline-variant rounded text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
        </div>

        <select name="role" class="px-md py-sm bg-surface border border-outline-variant rounded text-on-surface-variant text-sm focus:border-primary focus:ring-0">
            <option value="">Todos los roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
            @endforeach
        </select>

        <select name="status" class="px-md py-sm bg-surface border border-outline-variant rounded text-on-surface-variant text-sm focus:border-primary focus:ring-0">
            <option value="">Cualquier estado</option>
            <option value="1" @selected(request('status') === '1')>Activo</option>
            <option value="0" @selected(request('status') === '0')>Inactivo</option>
        </select>

        <x-ui.per-page-select />

        <button type="submit" class="px-lg py-sm border border-outline text-on-surface rounded hover:border-primary hover:text-primary transition-colors text-body-md">
            Filtrar
        </button>
        @if (request('search') || request('role') || request()->filled('status'))
            <a href="{{ route('user.index') }}" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">Limpiar</a>
        @endif
    </form>

    {{-- Tabla --}}
    <div class="bg-surface-container border border-surface-variant rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-variant text-on-surface-variant font-mono-label text-label-caps uppercase">
                    <th class="text-left px-lg py-md font-medium">ID</th>
                    <x-ui.sortable-th column="name" label="Usuario" />
                    <th class="text-left px-lg py-md font-medium">Rol</th>
                    <th class="text-left px-lg py-md font-medium">Estado</th>
                    <x-ui.sortable-th column="created_at" label="Registro" />
                    <th class="text-right px-lg py-md font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-variant">
                @forelse ($users as $user)
                    <tr class="hover:bg-surface-variant/20 transition-colors">
                        <td class="px-lg py-md font-mono-label text-on-surface-variant">#U-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-lg py-md">
                            <div class="flex items-center gap-md">
                                <div class="w-9 h-9 rounded-full border border-outline-variant bg-surface-container-lowest flex items-center justify-center text-on-surface font-bold text-xs">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-on-surface">{{ $user->name }}</p>
                                    <p class="text-on-surface-variant/70 text-xs">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-lg py-md">
                            @forelse ($user->roles as $role)
                                <span class="inline-flex items-center px-sm py-1 rounded border border-primary/40 text-primary bg-primary/10 font-mono-label text-label-caps">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-on-surface-variant/50 text-xs">Sin rol</span>
                            @endforelse
                        </td>
                        <td class="px-lg py-md">
                            <div class="flex items-center gap-sm">
                                <div class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-primary shadow-[0_0_8px_rgba(255,193,116,0.8)]' : 'bg-surface-variant' }}"></div>
                                <span class="{{ $user->is_active ? 'text-on-surface' : 'text-on-surface-variant' }}">
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-lg py-md text-on-surface-variant">{{ $user->created_at->format('d M, Y') }}</td>
                        <td class="px-lg py-md">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('user.edit', $user) }}" class="p-2 rounded text-on-surface-variant hover:text-primary hover:bg-surface-variant/30 transition-colors" title="Editar">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <form action="{{ route('user.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded text-on-surface-variant hover:text-error hover:bg-surface-variant/30 transition-colors" title="Eliminar">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-lg py-xl text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">group</span>
                            Aún no hay usuarios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
@endsection