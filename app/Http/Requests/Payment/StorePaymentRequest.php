<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'raffle_id' => ['required', 'exists:raffles,id'],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'reference' => ['nullable', 'string', 'max:255'],
            'proof_image' => ['required', 'image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Debes seleccionar el cliente.',
            'raffle_id.required' => 'Debes seleccionar el sorteo.',
            'total_amount.required' => 'Debes indicar el monto pagado.',
            'payment_method_id.required' => 'Debes seleccionar el método de pago.',
            'proof_image.required' => 'Debes subir el comprobante de pago.',
            'proof_image.image' => 'El comprobante debe ser una imagen.',
        ];
    }
}
