@extends('layouts.base')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="flex items-center justify-center min-h-screen p-4">
    <div class="card-enter relative w-full max-w-md">
        <div class="bg-casino-dark/80 backdrop-blur-xl border border-casino-gold/30 rounded-2xl shadow-2xl shadow-black/50 p-8">

            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 mb-4 rounded-full bg-gradient-to-br from-casino-gold to-casino-red flex items-center justify-center shadow-lg shadow-casino-gold/30">
                    <svg class="w-8 h-8 text-casino-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <circle cx="12" cy="12" r="4" />
                    </svg>
                </div>
                <h1 class="font-display text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-casino-gold to-yellow-300">
                    {{ env('SYSTEM_TITLE', 'LARAVEL') }}
                </h1>
                <p class="text-casino-white/50 text-sm mt-1">Ingresa para gestionar tus sorteos</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-casino-red/50 bg-casino-red/10 px-4 py-3">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-300 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-casino-white/70 mb-1.5">Correo electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-casino-gold/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="tucorreo@ejemplo.com"
                               class="w-full pl-10 pr-4 py-3 bg-white/5 border border-casino-gold/20 rounded-lg text-casino-white placeholder-casino-white/30
                                      transition-all duration-300 ease-out
                                      focus:outline-none focus:border-casino-gold focus:ring-2 focus:ring-casino-gold/40 focus:bg-white/10
                                      hover:border-casino-gold/40">
                    </div>
                    @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-casino-white/70 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-casino-gold/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-3 bg-white/5 border border-casino-gold/20 rounded-lg text-casino-white placeholder-casino-white/30
                                      transition-all duration-300 ease-out
                                      focus:outline-none focus:border-casino-gold focus:ring-2 focus:ring-casino-gold/40 focus:bg-white/10
                                      hover:border-casino-gold/40">
                    </div>
                    @error('password') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-casino-white/60 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded accent-casino-gold w-4 h-4">
                        Recordarme
                    </label>
                    <a href="#" class="text-casino-gold/80 hover:text-casino-gold transition-colors duration-200 hover:underline underline-offset-4">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button type="submit"
                        class="btn-shimmer w-full py-3 rounded-lg font-semibold text-casino-dark
                               bg-[linear-gradient(110deg,#F59E0B,45%,#DC2626,55%,#F59E0B)] bg-[length:200%_100%]
                               shadow-lg shadow-casino-gold/30
                               transition-all duration-300 ease-out
                               hover:shadow-xl hover:shadow-casino-gold/50 hover:scale-[1.02]
                               active:scale-[0.98]">
                    Iniciar Sesión
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-casino-white/50">
                ¿No tienes una cuenta?
                <a href="#" class="text-casino-gold font-semibold hover:text-yellow-300 transition-colors duration-200 hover:underline underline-offset-4">
                    Regístrate
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
