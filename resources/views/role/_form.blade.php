@csrf
<div class="bg-surface-container border border-surface-variant rounded-lg p-lg space-y-md mb-lg">
    <div>
        <label class="block text-body-md text-on-surface-variant mb-xs" for="name">Nombre del rol *</label>
        <input type="text" id="name" name="name"
               class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
               value="{{ old('name', $role->name ?? '') }}" required>
        @error('name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="bg-surface-container border border-surface-variant rounded-lg p-lg mb-lg">
    <h3 class="text-headline-md text-on-surface mb-md">Permisos</h3>
    @php
        $selected = old('permissions', isset($role) ? $role->permissions->pluck('id')->toArray() : []);
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-sm">
        @foreach ($permissions as $permission)
            <label class="flex items-center gap-sm px-md py-sm bg-surface border border-outline-variant rounded cursor-pointer hover:border-primary/50 transition-colors">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                       class="w-4 h-4 accent-primary"
                       @checked(in_array($permission->id, $selected))>
                <span class="text-body-md text-on-surface">{{ $permission->name }}</span>
            </label>
        @endforeach
    </div>
    @error('permissions') <p class="mt-2 text-xs text-error">{{ $message }}</p> @enderror
</div>

<div class="flex flex-col md:flex-row justify-end items-center gap-md">
    <a href="{{ route('role.index') }}"
       class="w-full md:w-auto text-center px-lg py-sm font-bold text-on-surface border border-outline-variant rounded-lg hover:border-primary hover:text-primary transition-colors">
        Cancelar
    </a>
    <button type="submit"
            class="w-full md:w-auto px-xl py-sm font-bold bg-primary-container text-on-primary-container rounded-lg hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all flex items-center justify-center gap-sm">
        <span class="material-symbols-outlined">save</span>
        Guardar Rol
    </button>
</div>