<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | {{ env('SYSTEM_TITLE', config('app.name')) }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&family=JetBrains+Mono:wght@500&family=Geist:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container-lowest min-h-screen flex flex-col font-admin text-on-surface overflow-x-hidden selection:bg-primary-container selection:text-on-primary-container">

    {{-- Fondo: silueta de montañas + línea dorada ambiental (puramente decorativo) --}}
    <x-background-animation />

    <main class="relative z-10 flex-grow flex items-center justify-center p-md">
        <div class="w-full max-w-[440px] flex flex-col items-center">

            <div class="mb-xl text-center">
                <h1 class="text-display-lg-mobile md:text-display-lg text-primary tracking-tighter mb-base">
                    {{ env('SYSTEM_TITLE', 'SORTEOS') }}
                </h1>
                <p class="text-label-caps text-on-surface-variant uppercase tracking-widest">
                    Exclusividad &amp; Fortuna
                </p>
            </div>

            <div class="w-full p-lg rounded-xl flex flex-col gap-lg motion-safe:animate-fade-slide-up
                        bg-surface-container/70 backdrop-blur-xl border border-surface-variant/50
                        shadow-[0_8px_32px_0_rgba(0,0,0,0.37)]">

                <div class="flex flex-col gap-sm">
                    <h2 class="text-headline-md text-center text-on-surface">Iniciar Sesión</h2>
                    <p class="text-body-md text-center text-on-surface-variant">Bienvenido de nuevo al panel de sorteos.</p>
                </div>

                @if ($errors->any())
                    <div class="rounded-lg border border-error/40 bg-error-container/20 px-4 py-3">
                        @foreach ($errors->all() as $error)
                            <p class="text-body-md text-error">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-md">
                    @csrf

                    <div class="flex flex-col gap-xs">
                        <label class="text-label-caps text-on-surface-variant" for="email">Correo Electrónico</label>
                        <div class="relative group">
                            <span class="js-field-icon material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-primary">mail</span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                   placeholder="usuario@elite.com"
                                   class="w-full h-14 bg-surface-container-low border border-outline-variant rounded-lg pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary-container transition-all">
                        </div>
                    </div>

                    <div class="flex flex-col gap-xs">
                        <div class="flex justify-between items-end">
                            <label class="text-label-caps text-on-surface-variant" for="password">Contraseña</label>
                            {{-- Ruta aún no creada (password.request) — dejo el enlace visible pero deshabilitado --}}
                            <span class="text-label-caps text-on-surface-variant/40 cursor-not-allowed" title="Próximamente">
                                ¿Olvidaste tu contraseña?
                            </span>
                        </div>
                        <div class="relative group">
                            <span class="js-field-icon material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-primary">lock</span>
                            <input id="password" name="password" type="password" required placeholder="••••••••"
                                   class="w-full h-14 bg-surface-container-low border border-outline-variant rounded-lg pl-12 pr-12 text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary-container transition-all">
                            <button type="button" id="toggle-password"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface">
                                <span class="material-symbols-outlined" id="toggle-password-icon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-body-md text-on-surface-variant cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded accent-primary-container w-4 h-4">
                        Recordarme
                    </label>

                    <button type="submit"
                        class="w-full h-14 bg-primary-container text-on-primary-container text-body-lg rounded-lg font-bold cursor-pointer
                                   hover:bg-primary hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] active:scale-[0.98] transition-all
                                   flex items-center justify-center gap-2 mt-sm">
                        <span>Entrar</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>

                <div class="relative flex items-center py-sm">
                    <div class="flex-grow border-t border-outline-variant"></div>
                    <span class="mx-4 text-label-caps text-on-surface-variant">O</span>
                    <div class="flex-grow border-t border-outline-variant"></div>
                </div>

                <div class="text-center">
                    <p class="text-body-md text-on-surface-variant mb-md">¿No tienes una cuenta aún?</p>
                    {{-- Ruta aún no creada (register) --}}
                    <a href="{{ route('register') }}" class="w-full h-14 inline-flex items-center justify-center bg-transparent border border-outline-variant text-on-surface-variant font-semibold rounded-lg transition-all hover:border-primary hover:text-primary hover:bg-primary/10 no-underline">
                        Crear Cuenta
                    </a>
                </div>
            </div>

            <footer class="mt-xl text-center flex flex-col gap-sm">
                <p class="text-label-caps text-on-surface-variant opacity-60">
                    &copy; {{ date('Y') }} {{ env('SYSTEM_TITLE', 'SORTEOS') }}.
                </p>
                <div class="flex justify-center gap-lg">
                    <a class="text-label-caps text-on-surface-variant hover:text-primary transition-colors" href="#">Términos</a>
                    <a class="text-label-caps text-on-surface-variant hover:text-primary transition-colors" href="#">Privacidad</a>
                    <a class="text-label-caps text-on-surface-variant hover:text-primary transition-colors" href="#">Soporte</a>
                </div>
            </footer>
        </div>
    </main>

    <script>
        // Respeta "reducir movimiento": si el usuario lo activó en su sistema,
        // quitamos la animación de la línea de montaña por completo.
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.getElementById('mountain-anim')?.remove();
        }

        // Rellena el ícono (sobre/candado) mientras el campo tiene el foco
        document.querySelectorAll('.js-field-icon').forEach((icon) => {
            const input = icon.parentElement.querySelector('input');
            input?.addEventListener('focus', () => icon.style.fontVariationSettings = "'FILL' 1");
            input?.addEventListener('blur', () => icon.style.fontVariationSettings = "'FILL' 0");
        });

        // Mostrar/ocultar contraseña
        const toggleBtn = document.getElementById('toggle-password');
        const toggleIcon = document.getElementById('toggle-password-icon');
        const passwordInput = document.getElementById('password');

        toggleBtn?.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            toggleIcon.textContent = isHidden ? 'visibility_off' : 'visibility';
        });
    </script>
</body>
</html>