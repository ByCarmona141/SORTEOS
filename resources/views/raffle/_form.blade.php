@csrf

<!-- Sección 1: Información General -->
<div class="glass-card p-6 mb-6">
    <h2 class="text-2xl font-bold text-md-primary mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-md-primary-container">info</span>
        Información General
    </h2>
    <div class="space-y-4">
        <div>
            <label class="premium-label" for="name">Nombre del Sorteo *</label>
            <input type="text" id="name" name="name" class="premium-input"
                   placeholder="Ej. Gran Rifa de Lujo 2026"
                   value="{{ old('name', $raffle->name ?? '') }}" required>
            @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="premium-label" for="description">Descripción</label>
            <textarea id="description" name="description" rows="4" class="premium-input resize-none"
                      placeholder="Detalles sobre el sorteo, premios y condiciones especiales...">{{ old('description', $raffle->description ?? '') }}</textarea>
            @error('description') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<!-- Sección 2: Configuración Principal -->
<div class="glass-card p-6 mb-6">
    <h2 class="text-2xl font-bold text-md-primary mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-md-primary-container">settings</span>
        Configuración Principal
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="premium-label" for="ticket_count">Cantidad de Boletos *</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-md-on-surface-variant">tag</span>
                <input type="number" id="ticket_count" name="ticket_count" min="1" class="premium-input pl-10"
                       placeholder="Ej. 1000" value="{{ old('ticket_count', $raffle->ticket_count ?? '') }}" required>
            </div>
            @error('ticket_count') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="premium-label" for="ticket_price">Precio por Boleto (MXN) *</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-md-on-surface-variant">attach_money</span>
                <input type="number" id="ticket_price" name="ticket_price" min="0" step="0.01" class="premium-input pl-10"
                       placeholder="0.00" value="{{ old('ticket_price', $raffle->ticket_price ?? '') }}" required>
            </div>
            @error('ticket_price') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="premium-label" for="opportunities">Oportunidades por Boleto</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-md-on-surface-variant">repeat</span>
                <input type="number" id="opportunities" name="opportunities" min="1" class="premium-input pl-10"
                       value="{{ old('opportunities', $raffle->opportunities ?? 1) }}">
            </div>
            @error('opportunities') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="premium-label" for="status_id">Estado Inicial *</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-md-on-surface-variant">flag</span>
                <select id="status_id" name="status_id" class="premium-input pl-10 appearance-none">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" @selected(old('status_id', $raffle->status_id ?? '') == $status->id)>
                            {{ ucfirst($status->name) }}
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-md-on-surface-variant pointer-events-none">expand_more</span>
            </div>
            @error('status_id') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<!-- Sección 3: Fechas y Reglas Especiales -->
<div class="glass-card p-6 mb-6">
    <h2 class="text-2xl font-bold text-md-primary mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-md-primary-container">event</span>
        Fechas y Reglas Especiales
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
            <label class="premium-label" for="draw_date">Fecha y Hora del Sorteo</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-md-on-surface-variant">calendar_month</span>
                <input type="datetime-local" id="draw_date" name="draw_date" class="premium-input pl-10"
                       value="{{ old('draw_date', isset($raffle->draw_date) ? $raffle->draw_date->format('Y-m-d\TH:i') : '') }}">
            </div>
            @error('draw_date') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="premium-label" for="reservation_expiration_hours">Horas de Expiración de Reserva</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-md-on-surface-variant">hourglass_empty</span>
                <input type="number" id="reservation_expiration_hours" name="reservation_expiration_hours" min="1" max="255" class="premium-input pl-10"
                       placeholder="Dejar vacío para usar el global"
                       value="{{ old('reservation_expiration_hours', $raffle->reservation_expiration_hours ?? '') }}">
            </div>
            @error('reservation_expiration_hours') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="premium-label" for="draw_trigger_percentage">% Mínimo para Realizar Sorteo</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-md-on-surface-variant">percent</span>
                <input type="number" id="draw_trigger_percentage" name="draw_trigger_percentage" min="1" max="100" class="premium-input pl-10"
                       placeholder="Ej. 80 para 80%"
                       value="{{ old('draw_trigger_percentage', $raffle->draw_trigger_percentage ?? '') }}">
            </div>
            @error('draw_trigger_percentage') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<!-- Barra de acciones -->
<div class="flex flex-col md:flex-row justify-end items-center gap-4 pt-6 border-t border-md-outline-variant/30">
    <a href="{{ route('raffle.index') }}"
       class="w-full md:w-auto text-center px-6 py-2.5 font-bold text-md-on-surface border border-md-outline-variant/50 rounded-lg hover:border-md-primary-container hover:text-md-primary-container transition-colors">
        Cancelar
    </a>
    <button type="submit"
            class="w-full md:w-auto px-8 py-2.5 font-bold bg-md-primary-container text-[#111415] rounded-lg shadow-[0_0_15px_rgba(245,158,11,0.2)] hover:brightness-110 hover:shadow-[0_0_25px_rgba(245,158,11,0.4)] transition-all flex items-center justify-center gap-2">
        <span class="material-symbols-outlined">save</span>
        Guardar Sorteo
    </button>
</div>
