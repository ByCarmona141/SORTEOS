@csrf
<div class="bg-surface-container border border-surface-variant rounded-lg p-lg space-y-md">
    <div>
        <label class="block text-body-md text-on-surface-variant mb-xs" for="title">Nombre del premio *</label>
        <input type="text" id="title" name="title"
               class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
               value="{{ old('title', $prize->title ?? '') }}" required>
        @error('title') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-body-md text-on-surface-variant mb-xs" for="description">Descripción</label>
        <textarea id="description" name="description" rows="3"
               class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none">{{ old('description', $prize->description ?? '') }}</textarea>
        @error('description') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="type_id">Categoría *</label>
            <select id="type_id" name="type_id"
                    class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                <option value="">Selecciona una categoría</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" @selected(old('type_id', $prize->type_id ?? '') == $type->id)>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('type_id') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="position">Posición *</label>
            <input type="number" id="position" name="position" min="1"
                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                   value="{{ old('position', $prize->position ?? $nextPosition ?? 1) }}" required>
            @error('position') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="amount">Valor en efectivo (MXN)</label>
            <input type="number" id="amount" name="amount" min="0" step="0.01"
                   class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                   value="{{ old('amount', $prize->amount ?? '') }}" placeholder="Solo si el premio es dinero">
            @error('amount') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-body-md text-on-surface-variant mb-xs" for="image">Imagen del premio</label>
        @if (isset($prize) && $prize->image_path)
            <div class="mb-sm">
                <img src="{{ asset('storage/' . $prize->image_path) }}"
                     class="w-24 h-24 object-cover rounded border border-outline-variant" alt="{{ $prize->title }}">
            </div>
        @endif
        <input type="file" id="image" name="image" accept="image/*"
               class="w-full text-on-surface-variant file:mr-md file:py-sm file:px-md file:rounded file:border-0 file:bg-primary-container file:text-on-primary-container file:font-bold file:cursor-pointer">
        @error('image') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="flex flex-col md:flex-row justify-end items-center gap-md pt-lg">
    <a href="{{ route('raffle.prize.index', $raffle) }}"
       class="w-full md:w-auto text-center px-lg py-sm font-bold text-on-surface border border-outline-variant rounded-lg hover:border-primary hover:text-primary transition-colors">
        Cancelar
    </a>
    <button type="submit"
            class="w-full md:w-auto px-xl py-sm font-bold bg-primary-container text-on-primary-container rounded-lg hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all flex items-center justify-center gap-sm">
        <span class="material-symbols-outlined">save</span>
        Guardar Premio
    </button>
</div>
