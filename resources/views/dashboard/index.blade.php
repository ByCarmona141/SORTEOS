@extends('layouts.main')

@section('title', 'Escritorio')

@section('content')
    <div class="flex justify-between items-end motion-safe:animate-fade-slide-up">
        <div>
            <h2 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter">Resumen Global</h2>
            <p class="text-body-md text-on-surface-variant mt-xs">Métricas de rendimiento en tiempo real.</p>
        </div>
        <div class="flex gap-md">
            <a href="#" class="px-lg py-sm border border-outline text-on-surface rounded hover:border-primary hover:text-primary transition-colors text-body-md">
                Validar Pagos
            </a>
            <a href="#" class="px-lg py-sm bg-primary text-on-primary rounded font-bold hover:shadow-[0_0_15px_rgba(255,193,116,0.3)] transition-all text-body-md">
                Crear Sorteo
            </a>
        </div>
    </div>

    {{-- KPIs: entrada escalonada, cada tarjeta aparece un poco después que la anterior --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">

        <x-ui.stat-card label="Ventas Hoy" value="$0.00" />

        <x-ui.stat-card label="Ingresos Totales" value="$0.00" />

        <x-ui.stat-card label="Boletos Vendidos" value="0" />

        {{-- Tarjeta que necesita atención: usa el mismo "halo dorado" del login,
        para que ese brillo siga significando lo mismo en toda la app: "esto importa ahora" --}}
        <x-ui.stat-card label="Pagos Pendientes" value="0" highlight=true hint="acción req." />

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">

        <div class="lg:col-span-2 bg-surface-container border border-surface-variant rounded-lg p-lg flex flex-col">
            <div class="flex justify-between items-center mb-lg">
                <h3 class="text-headline-md font-semibold">Velocidad de Ventas</h3>
                <select class="bg-surface border border-outline-variant text-on-surface-variant rounded text-sm focus:border-primary focus:ring-0">
                    <option>Últimos 30 días</option>
                    <option>Últimos 7 días</option>
                </select>
            </div>

            <div class="flex-1 bg-surface-dim rounded border border-surface-variant flex items-center justify-center relative overflow-hidden min-h-[300px]">
                <div class="absolute inset-0 bg-gradient-to-t from-primary/10 to-transparent opacity-50"></div>
                <svg class="absolute bottom-0 w-full h-full text-primary" preserveAspectRatio="none" viewBox="0 0 100 100">
                    {{-- pathLength="1" hace que el dibujo de la línea sea exacto sin adivinar su longitud real --}}
                    <path d="M0,50 Q25,80 50,40 T100,20" fill="none" stroke="currentColor" stroke-width="0.5"
                          pathLength="1" class="motion-safe:animate-dash-draw"></path>
                </svg>
                <p class="text-on-surface-variant/50 font-mono-label text-label-caps absolute">
                    Aún no hay datos de ventas para graficar
                </p>
            </div>
        </div>

        <div class="bg-surface-container border border-surface-variant rounded-lg p-lg flex flex-col">
            <h3 class="text-headline-md font-semibold mb-lg">Actividad Reciente</h3>

            <div class="flex-1 space-y-md">
                <p class="text-body-md text-on-surface-variant">
                    Aquí aparecerán los eventos del sistema (pagos validados, sorteos creados, etc.) en cuanto estén conectados.
                </p>
            </div>
        </div>
    </div>

@endsection