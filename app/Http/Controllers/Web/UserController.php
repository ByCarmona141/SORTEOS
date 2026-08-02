<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Http\Traits\Sortable;
use App\Http\Requests\UserRequest;

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
        $this->authorize('viewAny', User::class);

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
        $this->authorize('create', User::class);
        $roles = Role::all();
        $user = new User();

        return view('user.create', compact('roles', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request) {
        $this->authorize('create', User::class);

        $validated = $request->validated();

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
        $this->authorize('update', $user);

        $user->load('roles');
        $roles = Role::all();

        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user) {
        $this->authorize('update', $user);

        $validated = $request->validated();

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
        $this->authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Usuario eliminado.');
    }
}
