<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Muestra el formulario de edición del propio perfil.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Guarda los cambios del perfil.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'    => ['required', 'string', 'max:10', Rule::unique('users', 'phone')->ignore($user->id)],
            // 'required_with:password' = solo obligatorio si el campo "password" viene lleno.
            // Así no molestamos al usuario si solo va a cambiar su nombre.
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Si el usuario quiere una contraseña nueva, primero confirmamos que sepa la actual.
        if (!empty($validated['password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'La contraseña actual no es correcta.'])
                    ->onlyInput('name', 'email', 'phone');
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Perfil actualizado correctamente.');
    }
}