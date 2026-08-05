<?php

namespace App\Http\Requests\Prize;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePrizeRequest extends FormRequest
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
        $raffle = $this->route('raffle');
        $prize = $this->route('prize');

        return [
            'type_id' => ['required', 'exists:types,id'],
            'position' => [
                'required', 'integer', 'min:1', 'max:255',
                Rule::unique('prizes')
                    ->where(fn ($q) => $q->where('raffle_id', $raffle->id)) // No permite dos premios con el mismo lugar en el mismo sorteo
                    ->ignore($prize->id), // Ignora el premio actual para la validación
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'position.unique' => 'Ya existe un premio con ese lugar en este sorteo.',
        ];
    }
}
