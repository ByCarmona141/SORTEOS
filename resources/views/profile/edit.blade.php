@extends('layouts.main')

@section('title', 'Mi Perfil')

@section('content')

    <div class="mb-lg">
        <h1 class="text-display-lg-mobile lg:text-display-lg font-bold tracking-tighter">Gestión de Perfil</h1>
        <p class="text-body-md text-on-surface-variant mt-xs">Administra tu información personal y configuración de seguridad.</p>
    </div>

    @if (session('status'))
        <div class="mb-lg rounded-lg border border-primary/30 bg-primary/10 px-md py-sm text-primary text-body-md">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-lg rounded-lg border border-error/30 bg-error/10 px-md py-sm space-y-1">
            @foreach ($errors->all() as $error)
                <p class="text-body-md text-error">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-lg">

        {{-- Columna izquierda: resumen del usuario --}}
        <div class="md:col-span-4">
            <div class="bg-surface-container border border-surface-variant rounded-xl p-lg flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-surface-container-high border border-outline flex items-center justify-center text-primary font-bold text-3xl mb-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h2 class="text-headline-md text-on-surface">{{ $user->name }}</h2>
                <p class="text-body-md text-on-surface-variant mb-md break-all">{{ $user->email }}</p>

                @if ($user->roles->isNotEmpty())
                    <span class="inline-flex items-center gap-1.5 px-sm py-1 rounded-full border border-primary/40 text-primary bg-primary/10 font-mono-label text-label-caps mb-md">
                        {{ $user->roles->first()->name }}
                    </span>
                @endif

                <div class="w-full border-t border-outline-variant pt-md mt-sm text-left">
                    <p class="font-mono-label text-label-caps text-on-surface-variant/70 mb-1">MIEMBRO DESDE</p>
                    <p class="text-body-md text-on-surface">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Columna derecha: formulario --}}
        <div class="md:col-span-8">
            <form action="{{ route('profile.update') }}" method="POST" class="flex flex-col gap-lg">
                @csrf
                @method('PUT')

                {{-- Información personal --}}
                <section class="bg-surface-container border border-surface-variant rounded-xl p-lg space-y-md">
                    <h3 class="text-headline-md text-on-surface border-b border-outline-variant pb-sm mb-sm">
                        Información Personal
                    </h3>

                    <div>
                        <label for="name" class="block text-body-md text-on-surface-variant mb-xs">Nombre completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                        @error('name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                        <div>
                            <label for="email" class="block text-body-md text-on-surface-variant mb-xs">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            @error('email') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-body-md text-on-surface-variant mb-xs">Teléfono</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required
                                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            @error('phone') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                {{-- Seguridad --}}
                <section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg space-y-md">
                    <div class="flex items-center gap-sm border-b border-outline-variant pb-sm mb-sm">
                        <span class="material-symbols-outlined text-primary">shield_lock</span>
                        <h3 class="text-headline-md text-on-surface">Seguridad</h3>
                    </div>

                    <p class="text-body-md text-on-surface-variant">
                        Deja estos campos vacíos si no quieres cambiar tu contraseña.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                        <div>
                            <label for="password" class="block text-body-md text-on-surface-variant mb-xs">Nueva contraseña</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                       class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors pr-10">
                                <button type="button"
                                        class="js-toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary"
                                        data-target="password">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </div>
                            @error('password') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-body-md text-on-surface-variant mb-xs">Confirmar contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                        </div>
                    </div>

                    <div class="pt-sm border-t border-outline-variant/50">
                        <label for="current_password" class="flex items-center gap-1 text-body-md text-primary mb-xs">
                            <span class="material-symbols-outlined text-sm">key</span>
                            Contraseña actual (solo si vas a cambiarla)
                        </label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full px-md py-sm bg-surface border border-primary/40 rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                        @error('current_password') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>
                </section>

                {{-- Botones --}}
                <div class="flex flex-col md:flex-row justify-end gap-md">
                    <a href="{{ route('dashboard') }}"
                       class="w-full md:w-auto text-center px-lg py-sm font-bold text-on-surface border border-outline-variant rounded-lg hover:border-primary hover:text-primary transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="w-full md:w-auto px-xl py-sm font-bold bg-primary-container text-on-primary-container rounded-lg hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all flex items-center justify-center gap-sm">
                        <span class="material-symbols-outlined">save</span>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.js-toggle-password').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            const icon = btn.querySelector('.material-symbols-outlined');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.textContent = show ? 'visibility_off' : 'visibility';
        });
    });
</script>
@endpush
