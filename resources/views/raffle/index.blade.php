@extends('layouts.main')

@section('title', 'Sorteos')

@section('content')
    <div class="flex justify-between items-end motion-safe:animate-fade-slide-up">
        <div>
            <h2 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter">Sorteos</h2>
            <p class="text-body-md text-on-surface-variant mt-xs">Administra todos los sorteos según su estado.</p>
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

    @if (session('error'))
        <div class="rounded border border-error/30 bg-error/10 px-md py-sm text-error text-body-md">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tarjetas: una por cada estado real de la tabla statuses --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-gutter">
        @foreach ($statuses as $status)
            @php
                $badge = \App\Models\Raffle::statusBadgeMap()[$status->name] ?? ['icon' => 'help'];
            @endphp
            <x-ui.stat-card
                :label="$status->name"
                :value="$status->raffles_count"
                :icon="$badge['icon']"
            />
        @endforeach
        <x-ui.stat-card label="Total de sorteos" :value="$raffles->total()" icon="confirmation_number" highlight="true" />
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
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
        </div>

        <x-ui.per-page-select />

        <button type="submit" class="px-lg py-sm border border-outline text-on-surface rounded hover:border-primary hover:text-primary transition-colors text-body-md">
            Filtrar
        </button>
        @if (request('search') || request('status'))
            <a href="{{ route('raffle.index') }}" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">
                Limpiar
            </a>
        @endif
    </form>

    {{-- Tabla --}}
    <div class="bg-surface-container border border-surface-variant rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-variant text-on-surface-variant font-mono-label text-label-caps uppercase">
                    <x-ui.sortable-th column="name" label="Sorteo" />
                    <th class="text-left px-lg py-md font-medium">Estado</th>
                    <x-ui.sortable-th column="ticket_price" label="Precio boleto" />
                    <th class="text-left px-lg py-md font-medium">Progreso</th>
                    <x-ui.sortable-th column="draw_date" label="Fecha sorteo" />
                    <th class="text-right px-lg py-md font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-variant">
                @forelse ($raffles as $raffle)
                    @php
                        $sold = $raffle->tickets_sold_percentage ?? 0;
                        $badge = $raffle->statusBadge();
                    @endphp
                    <tr class="hover:bg-surface-variant/20 transition-colors">
                        <td class="px-lg py-md">
                            <a href="{{ route('raffle.show', $raffle) }}" class="text-on-surface font-medium hover:text-primary transition-colors">
                                {{ $raffle->name }}
                            </a>
                            <p class="text-on-surface-variant/60 text-xs mt-0.5">{{ $raffle->ticket_count }} boletos</p>
                        </td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center gap-1.5 px-sm py-1 rounded-full text-xs font-medium border {{ $badge['classes'] }}">
                                <span class="material-symbols-outlined text-sm">{{ $badge['icon'] }}</span>
                                {{ $raffle->status->name ?? 'Pendiente' }}
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

                                @if (($raffle->status->name ?? null) !== 'Activo')
                                    @php
                                        $canActivate = $raffle->prizes_count > 0 && $raffle->tickets_count > 0;
                                    @endphp

                                    @if ($canActivate)
                                        <form action="{{ route('raffle.status', $raffle) }}" method="POST"
                                            onsubmit="return confirm('¿Activar este sorteo? Quedará visible para los clientes.');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Activo">
                                            <button type="submit" class="p-2 rounded text-on-surface-variant hover:text-primary hover:bg-surface-variant/30 transition-colors" title="Activar sorteo">
                                                <span class="material-symbols-outlined text-xl">play_circle</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 rounded text-on-surface-variant/30 cursor-not-allowed" title="Faltan premios y/o boletos generados para activar">
                                            <span class="material-symbols-outlined text-xl">play_circle</span>
                                        </span>
                                    @endif
                                @endif

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
