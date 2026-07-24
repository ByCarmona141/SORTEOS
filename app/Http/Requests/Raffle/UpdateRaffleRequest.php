<?php

namespace App\Http\Requests\Raffle;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRaffleRequest extends FormRequest
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
            'text' => ['nullable'],
            'ticket_count' => ['required', 'numeric'],
            'ticket_price' => ['required', 'numeric'],
            'opportunities' => ['required', 'numeric'],
            'draw_date' => ['date', 'nullable'],
            //'status' => 'required'
        ];
    }
}
