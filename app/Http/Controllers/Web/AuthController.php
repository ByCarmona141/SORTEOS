<?php
// app/Http/Controllers/Web/AuthController.php

namespace App\Http\Controllers\Web;

use Spatie\Permission\Models\Role;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLogin() {
        // Si ya está logueado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Regenerar sesión para seguridad
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    public function showRegister() {
        // Si ya está logueado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // ✅ PASO 1: Validar datos del formulario
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email', // No debe existir email igual
            'phone'    => 'required|string|unique:users,phone', // No debe existir teléfono igual
            'password' => 'required|string|min:8|confirmed', // Debe coincidir con password_confirmation
        ], [
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
        ]);
 
        // ✅ PASO 2: Encriptar la contraseña
        $validated['password'] = Hash::make($validated['password']);
 
        // ✅ PASO 3: Crear el usuario en la base de datos
        $user = User::create($validated);
 
        // ✅ PASO 4: Asignar el rol "client" usando Spatie Permission
        // ¿Por qué en try-catch? Por si el rol no existe en la BD
        try {
            // Buscar o crear el rol "client"
            $clientRole = Role::firstOrCreate(
                ['name' => 'client'],
                ['guard_name' => 'web'] // Especificamos que es para el guard 'web'
            );
            
            // Asignar el rol al usuario
            $user->assignRole($clientRole);
            
        } catch (\Exception $e) {
            // Si algo falla al asignar el rol, lo registramos pero seguimos
            \Log::warning('Error al asignar rol client al usuario: ' . $e->getMessage());
            // El usuario se crea igual, solo sin rol por ahora
        }
 
        // ✅ PASO 5: Iniciar sesión automáticamente con el usuario creado
        Auth::login($user);
 
        // ✅ PASO 6: Regenerar sesión (medida de seguridad)
        $request->session()->regenerate();
 
        // ✅ PASO 7: Redirigir al dashboard
        return redirect()->route('dashboard')->with('success', '¡Bienvenido! Tu cuenta ha sido creada como cliente.');
    }


    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
