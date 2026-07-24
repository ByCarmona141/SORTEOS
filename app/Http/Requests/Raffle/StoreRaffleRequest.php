<?php

namespace App\Http\Requests\Raffle;

use Illuminate\Foundation\Http\FormRequest;

class StoreRaffleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:254'],
            'description' => ['nullable'],
            'ticket_count' => ['required', 'numeric'],
            'ticket_price' => ['required', 'numeric'],
            'opportunities' => ['required', 'numeric'],
            'status_id' => ['required', 'exists:statuses,id'],
            'draw_date' => ['date', 'nullable'],
            'reservation_expiration_hours' => ['numeric', 'nullable'],
            'draw_trigger_percentage' => ['numeric', 'nullable'],
            'created_by' => ['nullable', 'exists:users,id'],
            //'status' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            // Mensajes personalizados en español
            'name.required' => 'El nombre del sorteo es obligatorio.',
            'ticket_count.required' => 'Debes indicar cuántos boletos tendrá el sorteo.',
            'ticket_price.required' => 'Debes indicar el precio del boleto.',
            'status_id.required' => 'Debes seleccionar un estado.',
        ];
    }
}
