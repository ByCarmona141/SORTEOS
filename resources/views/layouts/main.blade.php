<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') · Rifas de la Montaña</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-casino-dark text-casino-white font-body min-h-screen flex antialiased">

    {{-- Resplandor ambiental de fondo: un detalle sutil, no una animación --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-40 -left-32 h-96 w-96 rounded-full bg-casino-gold/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-casino-red/10 blur-3xl"></div>
    </div>

    {{-- Sidebar --}}
    <aside class="relative z-10 w-64 shrink-0 bg-casino-darker/95 border-r border-white/10 flex flex-col">

        <div class="relative flex items-center gap-3 px-6 py-6 border-b border-white/10">
            {{-- Firma visual: halo dorado pulsante detrás del logo --}}
            <span class="relative flex h-9 w-9 items-center justify-center">
                <span class="absolute inset-0 rounded-full bg-casino-gold/60 blur-md motion-safe:animate-glow"></span>
                <span class="relative flex h-9 w-9 items-center justify-center rounded-full bg-casino-gold text-casino-darker font-display font-bold text-sm">
                    RM
                </span>
            </span>
            <span class="font-display text-lg font-semibold tracking-tight">Rifas de la Montaña</span>
        </div>

        <nav class="flex-1 px-3 py-6 space-y-1">
            <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-casino-white/40">Panel</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-casino-gold/15 text-casino-gold' : 'text-casino-white/70 hover:bg-white/5 hover:text-casino-white' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('dashboard') ? 'bg-casino-gold' : 'bg-transparent' }}"></span>
                Escritorio
            </a>

            <a href="{{ route('user.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('user.*') ? 'bg-casino-gold/15 text-casino-gold' : 'text-casino-white/70 hover:bg-white/5 hover:text-casino-white' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('user.*') ? 'bg-casino-gold' : 'bg-transparent' }}"></span>
                Usuarios
            </a>
        </nav>

        <a href="{{ route('profile.edit') }}" class="px-4 py-4 border-t border-white/10 flex items-center gap-3 hover:bg-white/5 transition-colors">
            <div class="h-8 w-8 rounded-full bg-casino-red/20 border border-casino-red/40 flex items-center justify-center font-display text-xs text-casino-red">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-casino-white/40">Sesión activa</p>
            </div>
        </a>
    </aside>

    {{-- Columna derecha: header + contenido + footer --}}
    <div class="relative z-10 flex-1 flex flex-col min-w-0">

        <header class="h-16 flex items-center justify-between px-6 border-b border-white/10 bg-casino-dark/70 backdrop-blur">
            <div>
                <p class="text-xs text-casino-white/40">@yield('eyebrow', 'Panel administrativo')</p>
                <h1 class="font-display text-lg font-medium leading-tight">@yield('title', 'Escritorio')</h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-2 text-sm px-3 py-1.5 rounded-lg border border-white/10
                        text-casino-white/70 hover:text-casino-white hover:border-casino-gold/40 transition-colors">
                    <span class="h-6 w-6 rounded-full bg-casino-gold/20 flex items-center justify-center text-casino-gold text-xs font-display">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    Mi perfil
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-sm px-4 py-1.5 rounded-lg border border-casino-red/40 text-casino-red
                                hover:bg-casino-red/10 transition-colors">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-6 motion-safe:animate-fade-slide-up">
            @yield('content')
        </main>

        <footer class="px-6 py-4 border-t border-white/10 flex items-center justify-between text-xs text-casino-white/40">
            <span>&copy; {{ date('Y') }} Rifas de la Montaña</span>
            <span>v1.0</span>
        </footer>
    </div>

</body>
</html>