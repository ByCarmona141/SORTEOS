<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'], // No debe existir email igual
            'phone'    => ['required', 'string', 'unique:users,phone', 'regex:/^[0-9]{10}$/'], // No debe existir teléfono igual
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Debe coincidir con password_confirmation
        ];
    }

    public function messages(): array
    {
        return [
            // Mensajes personalizados en español
            'name.required'       => 'El nombre es obligatorio',
            'email.required'      => 'El email es obligatorio',
            'email.email'         => 'El email debe ser válido',
            'email.unique'        => 'Este email ya está registrado',
            'phone.required'      => 'El teléfono es obligatorio',
            'phone.unique'        => 'Este teléfono ya está registrado',
            'password.required'   => 'La contraseña es obligatoria',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed'  => 'Las contraseñas no coinciden',
        ];
    }
}
