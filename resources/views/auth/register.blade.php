@extends('layouts.auth')
 
@section('title', 'Login - Sorteos')

@section('content')
    {{-- HEADER --}}
    <header class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-outline-variant">
        <div class="flex justify-between items-center h-16 px-6 max-w-container-max mx-auto">

            <div class="font-display-lg text-display-lg-mobile md:text-display-lg font-bold text-primary">
                {{ env('SYSTEM_TITLE', 'RIFAS DE LA MONTAÑA') }}
                <p class="text-label-caps text-center text-on-surface-variant uppercase tracking-widest">
                    Exclusividad &amp; Fortuna
                </p>
            </div>

            <div class="hidden md:flex items-center gap-md">
                <span class="font-label-caps text-on-surface-variant">¿YA TIENES CUENTA?</span>

                {{-- 🔥 Ruta real --}}
                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </header>

    {{-- Fondo: silueta de montañas + línea dorada ambiental (puramente decorativo) --}}
    <x-background-animation />

    {{-- MAIN --}}
    <main class="flex-grow pt-32 pb-xl px-gutter">
        <div class="max-w-[1000px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-xl items-start">
            
            {{-- PANEL IZQUIERDO --}}
            <div class="lg:col-span-5 space-y-lg">
                <div>
                    <h1 class="text-display-lg text-on-background">Únete a la Élite.</h1>
                    <p class="text-body-lg text-on-surface-variant">
                        Accede a sorteos exclusivos de alta gama.
                    </p>
                </div>
            </div>

            {{-- PANEL DERECHO --}}
            <div class="lg:col-span-7">
                <div class="w-full p-lg rounded-xl flex flex-col gap-lg motion-safe:animate-fade-slide-up bg-surface-container/70 backdrop-blur-xl border border-surface-variant/50 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)]">
                    <form action="{{ route('register.post') }}" method="POST" class="glass-panel p-lg md:p-xl rounded-xl space-y-lg shadow-2xl relative">
                        @csrf
                        <div class="space-y-sm">
                            <h2 class="font-headline-md text-headline-md text-on-background">Crear nueva cuenta</h2>
                            <p class="font-body-md text-on-surface-variant">Completa tus datos oficiales para validar tus premios.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            {{-- Nombre --}}
                            <div class="md:col-span-2 space-y-sm">
                                <label class="font-label-caps text-label-caps text-on-surface-variant flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[16px]">person</span> NOMBRE COMPLETO
                                </label>
                                <div class="gold-glow flex items-center bg-surface-container-lowest border border-outline-variant rounded-lg">
                                    <input id="name" name="name" value="{{ old('name') }}" required autofocus class="w-full bg-transparent border-none text-on-surface font-body-md p-md" placeholder="Ej. Juan Pérez">
                                </div>

                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="space-y-sm">
                                <label class="font-label-caps text-on-surface-variant flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[16px]">mail</span> EMAIL
                                </label>
                                <div class="gold-glow flex items-center bg-surface-container-lowest border border-outline-variant rounded-lg">
                                    <input id="email" name="email" type="email" required value="{{ old('email') }}" required class="w-full bg-transparent border-none text-on-surface font-body-md p-md" placeholder="usuario@elite.com">
                                </div>

                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Teléfono --}}
                            <div class="space-y-sm">
                                <label class="font-label-caps text-on-surface-variant flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[16px]">phone_iphone</span> TELÉFONO
                                </label>
                                <div class="gold-glow flex items-center bg-surface-container-lowest border border-outline-variant rounded-lg">
                                    <input id="phone" name="phone" required value="{{ old('phone') }}" class="w-full bg-transparent border-none text-on-surface font-body-md p-md" placeholder="747 123 1234">
                                </div>

                                @error('phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="space-y-sm">
                                <label class="font-label-caps text-on-surface-variant flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[16px]">lock</span> CONTRASEÑA
                                </label>
                                <div class="gold-glow flex items-center bg-surface-container-lowest border border-outline-variant rounded-lg">
                                    <input id="password" name="password" type="password" required class="w-full bg-transparent border-none text-on-surface font-body-md p-md">
                                </div>
                                <p class="mt-1 text-xs text-slate-500">
                                    Mínimo 8 caracteres
                                </p>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm --}}
                            <div class="space-y-sm">
                                <label class="font-label-caps text-on-surface-variant flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[16px]">lock_reset</span> CONFIRMAR
                                </label>
                                <div class="gold-glow flex items-center bg-surface-container-lowest border border-outline-variant rounded-lg">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full bg-transparent border-none text-on-surface font-body-md p-md">
                                </div>

                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Terms --}}
                        <div class="flex items-start gap-md pt-md">
                            <input type="checkbox" name="terms" required class="h-5 w-5 accent-primary-container">
                            <label class="font-body-md text-label-caps text-on-surface-variant" for="terms">
                                Acepto los <a class="text-primary hover:underline" href="#">Términos y Condiciones</a> y la <a class="text-primary hover:underline" href="#">Política de Privacidad</a> de Rifas de la Montaña.
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full h-14 bg-primary-container text-on-primary-container text-body-lg rounded-lg font-bold cursor-pointer
                                   hover:bg-primary hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] active:scale-[0.98] transition-all
                                   flex items-center justify-center gap-2 mt-sm">
                            <span>Crear Cuenta</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="mt-auto py-lg border-t border-outline-variant bg-background/50">
        <div class="max-w-container-max mx-auto px-gutter flex justify-between">
            <p>© {{ date('Y') }} {{ env('SYSTEM_TITLE', 'RIFAS') }}</p>
            <div class="flex gap-lg">
                <a href="#">Privacidad</a>
                <a href="#">Términos</a>
            </div>
        </div>
    </footer>
@endsection