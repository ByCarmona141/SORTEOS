@csrf
<div class="bg-surface-container border border-surface-variant rounded-lg p-lg space-y-md">
    <div>
        <label class="block text-body-md text-on-surface-variant mb-xs" for="name">Nombre completo *</label>
        <input type="text" id="name" name="name"
               class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
               value="{{ old('name', $user->name ?? '') }}" required>
        @error('name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="email">Correo electrónico *</label>
            <input type="email" id="email" name="email"
                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                   value="{{ old('email', $user->email ?? '') }}" required>
            @error('email') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="phone">Teléfono *</label>
            <input type="text" id="phone" name="phone"
                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                   value="{{ old('phone', $user->phone ?? '') }}" required>
            @error('phone') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="password">
                {{ isset($user) ? 'Nueva contraseña' : 'Contraseña *' }}
            </label>
            <input type="password" id="password" name="password"
                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                   {{ isset($user) ? '' : 'required' }}>
            @if (isset($user))
                <p class="mt-1 text-xs text-on-surface-variant/60">Deja este campo vacío si no quieres cambiarla.</p>
            @endif
            @error('password') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="password_confirmation">Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-md items-center">
        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="role">Rol *</label>
            <select id="role" name="role"
                    class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                <option value="">Selecciona un rol</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected(old('role', $user->roles->first()->name ?? '') == $role->name)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center gap-sm pt-6">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                   class="w-4 h-4 accent-primary" @checked(old('is_active', $user->is_active ?? true))>
            <label for="is_active" class="text-body-md text-on-surface">Usuario activo</label>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-end items-center gap-md pt-lg">
    <a href="{{ route('user.index') }}"
       class="w-full md:w-auto text-center px-lg py-sm font-bold text-on-surface border border-outline-variant rounded-lg hover:border-primary hover:text-primary transition-colors">
        Cancelar
    </a>
    <button type="submit"
            class="w-full md:w-auto px-xl py-sm font-bold bg-primary-container text-on-primary-container rounded-lg hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all flex items-center justify-center gap-sm">
        <span class="material-symbols-outlined">save</span>
        Guardar Usuario
    </button>
</div>
