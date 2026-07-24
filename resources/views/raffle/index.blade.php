@extends('layouts.main')

@section('title', 'Sorteos')

@section('content')
    <div class="flex justify-between items-end motion-safe:animate-fade-slide-up">
        <div>
            <h2 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter">Sorteos</h2>
            <p class="text-body-md text-on-surface-variant mt-xs">Administra todos los sorteos activos, en preparación y finalizados.</p>
        </div>
        <a href="{{ route('raffle.create') }}" class="px-lg py-sm bg-primary text-on-primary rounded font-bold hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all text-body-md">
            Crear Sorteo
        </a>
    </div>

    @if (session('success'))
        <div class="rounded border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tarjetas resumen, mismas que el dashboard, ahora con ícono --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <x-ui.stat-card label="Activos" :value="$stats['active'] ?? 0" icon="check_circle" />
        <x-ui.stat-card label="En preparación" :value="$stats['draft'] ?? 0" icon="draft" />
        <x-ui.stat-card label="Finalizados" :value="$stats['finished'] ?? 0" icon="flag" />
        <x-ui.stat-card label="Total de sorteos" :value="$raffles->total()" icon="confirmation_number" />
    </div>

    {{-- Filtros --}}
    <form method="GET" class="flex flex-wrap items-center gap-sm">
        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar sorteo..."
                   class="w-full pl-10 pr-4 py-sm bg-surface border border-outline-variant rounded text-on-surface placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
        </div>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">flag</span>
            <select name="status" class="pl-10 pr-10 py-sm bg-surface border border-outline-variant rounded text-on-surface-variant text-sm focus:border-primary focus:ring-0 appearance-none">
                <option value="">Todos los estados</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->name }}" @selected(request('status') === $status->name)>
                        {{ ucfirst($status->name) }}
                    </option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
        </div>
        <button type="submit" class="px-lg py-sm border border-outline text-on-surface rounded hover:border-primary hover:text-primary transition-colors text-body-md">
            Filtrar
        </button>
        @if (request('search') || request('status'))
            <a href="{{ route('raffle.index') }}" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">
                Limpiar
            </a>
        @endif
    </form>

    {{-- Tabla, mismos colores que el resto del dashboard --}}
    <div class="bg-surface-container border border-surface-variant rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-variant text-on-surface-variant font-mono-label text-label-caps uppercase">
                    <th class="text-left px-lg py-md font-medium">Sorteo</th>
                    <th class="text-left px-lg py-md font-medium">Estado</th>
                    <th class="text-left px-lg py-md font-medium">Precio boleto</th>
                    <th class="text-left px-lg py-md font-medium">Progreso</th>
                    <th class="text-left px-lg py-md font-medium">Fecha sorteo</th>
                    <th class="text-right px-lg py-md font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-variant">
                @forelse ($raffles as $raffle)
                    @php
                        $sold = $raffle->tickets_sold_percentage ?? 0;
                        $statusStyles = [
                            'draft'    => ['bg-on-surface-variant/10 text-on-surface-variant border-outline-variant/40', 'draft'],
                            'active'   => ['bg-primary/10 text-primary border-primary/30', 'check_circle'],
                            'finished' => ['bg-secondary/10 text-secondary border-secondary/30', 'flag'],
                        ];
                        $statusLabels = [
                            'draft'    => 'En preparación',
                            'active'   => 'Activo',
                            'finished' => 'Finalizado',
                        ];
                        $statusKey = $raffle->status->name ?? 'draft';
                        [$statusClass, $statusIcon] = $statusStyles[$statusKey] ?? $statusStyles['draft'];
                    @endphp
                    <tr class="hover:bg-surface-variant/20 transition-colors">
                        <td class="px-lg py-md">
                            <a href="{{ route('raffle.show', $raffle) }}" class="text-on-surface font-medium hover:text-primary transition-colors">
                                {{ $raffle->name }}
                            </a>
                            <p class="text-on-surface-variant/60 text-xs mt-0.5">{{ $raffle->ticket_count }} boletos</p>
                        </td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center gap-1.5 px-sm py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                                <span class="material-symbols-outlined text-sm">{{ $statusIcon }}</span>
                                {{ $statusLabels[$statusKey] ?? 'En preparación' }}
                            </span>
                        </td>
                        <td class="px-lg py-md text-on-surface/80">
                            ${{ number_format($raffle->ticket_price, 2) }}
                        </td>
                        <td class="px-lg py-md">
                            <div class="flex items-center gap-2 w-32">
                                <div class="flex-1 h-1.5 bg-surface-variant rounded-full overflow-hidden">
                                    <div class="h-full bg-primary rounded-full" style="width: {{ min($sold, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-on-surface-variant w-9 text-right">{{ round($sold) }}%</span>
                            </div>
                        </td>
                        <td class="px-lg py-md text-on-surface/70">
                            {{ $raffle->draw_date?->format('d/m/Y') ?? 'Sin definir' }}
                        </td>
                        <td class="px-lg py-md">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('raffle.show', $raffle) }}" class="p-2 rounded text-on-surface-variant hover:text-primary hover:bg-surface-variant/30 transition-colors" title="Ver">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                <a href="{{ route('raffle.edit', $raffle) }}" class="p-2 rounded text-on-surface-variant hover:text-primary hover:bg-surface-variant/30 transition-colors" title="Editar">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <form action="{{ route('raffle.destroy', $raffle) }}" method="POST" onsubmit="return confirm('¿Eliminar este sorteo? Esta acción no se puede deshacer.');">
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
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">confirmation_number</span>
                            Aún no hay sorteos creados.
                            <a href="{{ route('raffle.create') }}" class="text-primary hover:underline underline-offset-4">Crea el primero</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $raffles->links() }}
    </div>
@endsection
