<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Http\Traits\Sortable;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    use Sortable;

    /**
     * Lista de usuarios con búsqueda, filtro por rol/estado y orden.
     */
    public function index(Request $request) {
        $query = User::with('roles')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('name', 'like', "%{$request->search}%")
                       ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->when($request->role, function ($q) use ($request) {
                $q->whereHas('roles', fn ($r) => $r->where('name', $request->role));
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('is_active', $request->status);
            });

        $this->applySorting($query, $request, ['name', 'email', 'created_at'], 'created_at');

        $users = $query->paginate($this->resolvePerPage($request))->withQueryString();

        $roles = Role::all();

        return view('user.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $roles = Role::all();

        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:10', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('user.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user) {
        return redirect()->route('user.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) {
        $user->load('roles');
        $roles = Role::all();

        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:10', Rule::unique('users', 'phone')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->is_active = $request->boolean('is_active', true);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // syncRoles reemplaza el rol anterior por el nuevo (un usuario, un rol visible en el select)
        $user->syncRoles([$validated['role']]);

        return redirect()->route('user.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) {
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Usuario eliminado.');
    }
}
