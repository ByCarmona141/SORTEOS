@csrf
<div class="bg-surface-container border border-surface-variant rounded-lg p-lg space-y-md">

    @if (!isset($payment))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div>
                <label class="block text-body-md text-on-surface-variant mb-xs" for="user_id">Cliente *</label>
                <select id="user_id" name="user_id" class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <option value="">Selecciona un cliente</option>
                    @foreach (\App\Models\User::orderBy('name')->get() as $client)
                        <option value="{{ $client->id }}" @selected(old('user_id') == $client->id)>{{ $client->name }} ({{ $client->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-body-md text-on-surface-variant mb-xs">Sorteo</label>
                <div class="w-full px-md py-sm bg-surface-container-lowest border border-outline-variant rounded text-on-surface-variant">
                    {{ $saleRaffle->name }}
                </div>
                <input type="hidden" name="raffle_id" value="{{ $saleRaffle->id }}">
                @foreach ($saleTickets as $ticket)
                    <input type="hidden" name="ticket_ids[]" value="{{ $ticket->id }}">
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="total_amount">Monto pagado *</label>
            <input type="number" step="0.01" min="0" id="total_amount" name="total_amount"
                class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
                value="{{ old('total_amount', isset($saleRaffle) ? $saleTickets->count() * $saleRaffle->ticket_price : ($payment->total_amount ?? '')) }}"
                @if(isset($saleRaffle)) readonly @endif required>
        </div>
        <div>
            <label class="block text-body-md text-on-surface-variant mb-xs" for="payment_method_id">Método de pago *</label>
            <select id="payment_method_id" name="payment_method_id" class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                @foreach ($paymentMethods as $method)
                    <option value="{{ $method->id }}" @selected(old('payment_method_id', $payment->payment_method_id ?? '') == $method->id)>{{ $method->name }}</option>
                @endforeach
            </select>
            @error('payment_method_id') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-body-md text-on-surface-variant mb-xs" for="reference">Referencia (opcional)</label>
        <input type="text" id="reference" name="reference"
               class="w-full px-md py-sm bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
               value="{{ old('reference', $payment->reference ?? '') }}">
        @error('reference') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-body-md text-on-surface-variant mb-xs" for="proof_image">
            Comprobante {{ isset($payment) ? '(deja vacío para conservar el actual)' : '*' }}
        </label>
        <input type="file" id="proof_image" name="proof_image" accept="image/*,application/pdf"
            class="w-full text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-primary-container file:text-on-primary-container file:font-bold">
        @if (isset($payment) && $payment->proof_image)
            <p class="mt-2 text-xs text-on-surface-variant">Comprobante actual: <a class="text-primary underline" href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank">verlo</a></p>
        @endif
        @error('proof_image') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="flex flex-col md:flex-row justify-end items-center gap-md pt-lg">
    <a href="{{ route('payment.index') }}"
       class="w-full md:w-auto text-center px-lg py-sm font-bold text-on-surface border border-outline-variant rounded-lg hover:border-primary hover:text-primary transition-colors">
        Cancelar
    </a>
    <button type="submit"
            class="w-full md:w-auto px-xl py-sm font-bold bg-primary-container text-on-primary-container rounded-lg hover:shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all flex items-center justify-center gap-sm">
        <span class="material-symbols-outlined">save</span>
        Guardar Pago
    </button>
</div>
