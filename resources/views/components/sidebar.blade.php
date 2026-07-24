<nav class="hidden md:flex flex-col h-full py-lg bg-surface-container-lowest fixed left-0 top-0 w-[280px] border-r border-outline-variant z-40">
    <div class="px-lg mb-xl">
        <h1 class="text-headline-md font-semibold text-primary">Rifas de la Montaña</h1>
        <p class="font-mono-label text-label-caps text-on-surface-variant mt-sm">Panel administrativo</p>
    </div>

    <ul class="flex-1 px-sm space-y-sm">
        <li>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-md px-md py-sm rounded transition-all
                      {{ request()->routeIs('dashboard')
                         ? 'text-primary border-l-4 border-primary bg-surface-container-low font-bold'
                         : 'text-on-surface-variant hover:text-on-surface hover:bg-primary-container/10 ml-[4px]' }}">
                <span class="material-symbols-outlined" @if(request()->routeIs('dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
                <span class="text-body-md">Escritorio</span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.index') }}"
               class="flex items-center gap-md px-md py-sm rounded transition-all ml-[4px]
                      {{ request()->routeIs('user.*')
                         ? 'text-primary border-l-4 border-primary bg-surface-container-low font-bold -ml-[4px]'
                         : 'text-on-surface-variant hover:text-on-surface hover:bg-primary-container/10' }}">
                <span class="material-symbols-outlined">group</span>
                <span class="text-body-md">Usuarios</span>
            </a>
        </li>

        <li>
            <a href="{{ route('raffle.index') }}"
               class="flex items-center gap-md px-md py-sm rounded transition-all ml-[4px]
                      {{ request()->routeIs('raffle.*')
                         ? 'text-primary border-l-4 border-primary bg-surface-container-low font-bold -ml-[4px]'
                         : 'text-on-surface-variant hover:text-on-surface hover:bg-primary-container/10' }}">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span class="text-body-md">Sorteos</span>
            </a>
        </li>

        @foreach ([['payments', 'Pagos'], ['help_center', 'Soporte']] as [$icon, $label])
            <li>
                <span class="flex items-center gap-md px-md py-sm rounded ml-[4px] text-on-surface-variant/40 cursor-not-allowed">
                    <span class="material-symbols-outlined">{{ $icon }}</span>
                    <span class="text-body-md">{{ $label }}</span>
                    <span class="ml-auto font-mono-label text-label-caps text-on-surface-variant/40">Pronto</span>
                </span>
            </li>
        @endforeach
    </ul>

    <div class="px-lg mt-auto pt-lg border-t border-outline-variant">
        <details class="group relative">
            <summary class="flex items-center gap-sm cursor-pointer list-none px-md py-sm rounded hover:bg-surface-container transition-colors">
                <span class="w-8 h-8 rounded-full bg-surface-container-high border border-outline flex items-center justify-center text-primary font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span class="text-body-md text-on-surface font-semibold truncate">{{ auth()->user()->name }}</span>
                <span class="material-symbols-outlined text-sm ml-auto group-open:rotate-180 transition-transform">expand_more</span>
            </summary>

            <div class="absolute bottom-full left-0 mb-2 w-full rounded-lg border border-outline-variant bg-surface-container-high overflow-hidden">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-sm px-md py-sm text-body-md text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-sm">person</span>
                    Mi perfil
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-sm px-md py-sm text-body-md text-error hover:bg-error/10 transition-colors">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </details>
    </div>
</nav>
