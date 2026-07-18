@extends('layouts.main')

@section('eyebrow', 'Mi cuenta')
@section('title', 'Editar perfil')

@section('content')
    <div class="max-w-lg">

        @if (session('status'))
            <div class="mb-5 rounded-lg border border-casino-gold/40 bg-casino-gold/10 px-4 py-3 text-sm text-casino-gold">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-casino-red/40 bg-casino-red/10 px-4 py-3">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-300">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="rounded-xl bg-casino-darker/60 border border-white/10 p-6 space-y-5">

            <div class="flex items-center gap-4 pb-5 border-b border-white/10">
                <div class="h-12 w-12 rounded-full bg-casino-red/20 border border-casino-red/40 flex items-center justify-center font-display text-lg text-casino-red">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-display text-lg">{{ $user->name }}</p>
                    <p class="text-xs text-casino-white/40">{{ $user->email }}</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm text-casino-white/70 mb-1.5">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-casino-white
                                  focus:outline-none focus:border-casino-gold focus:ring-2 focus:ring-casino-gold/30">
                </div>

                <div>
                    <label for="email" class="block text-sm text-casino-white/70 mb-1.5">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-casino-white
                                  focus:outline-none focus:border-casino-gold focus:ring-2 focus:ring-casino-gold/30">
                </div>

                <div>
                    <label for="phone" class="block text-sm text-casino-white/70 mb-1.5">Teléfono</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required
                           class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-casino-white
                                  focus:outline-none focus:border-casino-gold focus:ring-2 focus:ring-casino-gold/30">
                </div>

                <div class="pt-4 border-t border-white/10">
                    <p class="text-xs text-casino-white/40 mb-4">Deja estos campos vacíos si no quieres cambiar tu contraseña.</p>

                    <div class="mb-4">
                        <label for="current_password" class="block text-sm text-casino-white/70 mb-1.5">Contraseña actual</label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-casino-white
                                    focus:outline-none focus:border-casino-gold focus:ring-2 focus:ring-casino-gold/30">
                        <p class="mt-1 text-xs text-casino-white/40">Solo necesaria si vas a cambiar tu contraseña.</p>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm text-casino-white/70 mb-1.5">Nueva contraseña</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-casino-white
                                    focus:outline-none focus:border-casino-gold focus:ring-2 focus:ring-casino-gold/30">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm text-casino-white/70 mb-1.5">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-casino-white
                                    focus:outline-none focus:border-casino-gold focus:ring-2 focus:ring-casino-gold/30">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-lg font-medium text-casino-darker bg-casino-gold
                               hover:brightness-110 transition">
                    Guardar cambios
                </button>
            </form>
        </div>
    </div>
@endsection