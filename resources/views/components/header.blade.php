<header class="h-16 px-6 flex justify-between items-center bg-background/80 backdrop-blur-xl border-b border-outline-variant sticky top-0 z-30">
    <div class="flex items-center gap-md text-on-surface-variant">
        <span class="material-symbols-outlined">search</span>
        <input class="bg-transparent border-none focus:ring-0 text-body-md w-64 placeholder:text-on-surface-variant/50"
               placeholder="Buscar boletos, usuarios..." type="text" disabled>
    </div>

    <div class="flex items-center gap-lg">
        <div class="relative">
            <span class="material-symbols-outlined text-primary cursor-pointer hover:scale-110 transition-transform">notifications</span>
            <span class="absolute -top-1 -right-1 flex h-2 w-2">
                <span class="motion-safe:animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-error"></span>
            </span>
        </div>
        <span class="text-body-md text-on-surface font-semibold">{{ Str::before(auth()->user()->name, ' ') }}</span>
    </div>
</header>