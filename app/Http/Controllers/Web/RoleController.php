<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('permissions')->orderBy('name')->paginate(10);

        return view('role.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

        $permissionNames = Permission::whereIn('id', $validated['permissions'] ?? [])->pluck('name');
        $role->syncPermissions($permissionNames);

        return redirect()->route('role.index')->with('success', 'Rol creado exitosamente.');
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::orderBy('name')->get();

        return view('role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update(['name' => $validated['name']]);

        $permissionNames = Permission::whereIn('id', $validated['permissions'] ?? [])->pluck('name');
        $role->syncPermissions($permissionNames);

        return redirect()->route('role.index')->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Admin', 'User'])) {
            return redirect()->route('role.index')->with('error', 'Este rol es del sistema y no se puede eliminar.');
        }

        $role->delete();

        return redirect()->route('role.index')->with('success', 'Rol eliminado.');
    }
}
