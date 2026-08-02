<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        // Policy: Llama al método 'viewAny' de la UserPolicy
        $this->authorize('viewAny', User::class);

        $users = User::all();

        return UserResource::collection($users)->response()->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        // Usar transacción para asegurar que todo se ejecute correctamente
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => $request->is_active ?? true,
            ]);

            // Si se proporciona el rol
            if ($request->has('role') && $request->role) {
                // Verificar que el rol existe
                $roleExists = Role::where('name', $request->role)->exists();

                // Si el rol no existe, se retorna un error
                if (!$roleExists) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => "El rol '{$request->role}' no existe"
                    ], 422);
                }

                // Asignar rol al usuario
                $user->assignRole($request->role);
            } else {
                // Si no se proporciona rol, asigna rol por defecto 'User'
                $user->assignRole('User');
            }

            // Recargar relaciones para incluir en la respuesta
            $user->load('roles', 'permissions');

            // Confirmar transacción
            DB::commit();

            return UserResource::make($user)
                ->additional([
                    'success' => true,
                    'message' => 'Usuario creado exitosamente'
                ])
                ->response()
                ->setStatusCode(201);

        } catch (Exception $e) {
            // Revertir cambios si hay error
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {
        $this->authorize('view', User::class);

        return UserResource::make($user)->response()->setStatusCode(200);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', User::class);

        DB::beginTransaction();

        try {
            $dataToUpdate = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Solo actualizar contraseña si se proporciona
            if ($request->filled('password')) {
                $dataToUpdate['password'] = Hash::make($request->password);
            }

            // Actualizar is_active si se proporciona
            if ($request->has('is_active')) {
                $dataToUpdate['is_active'] = $request->is_active;
            }

            // Actualizar usuario
            $user->update($dataToUpdate);

            // Actualizar rol si se proporciona
            if ($request->has('role')) {
                if ($request->role) {
                    // Verificar que el rol existe
                    $roleExists = Role::where('name', $request->role)->exists();

                    if (!$roleExists) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "El rol '{$request->role}' no existe"
                        ], 422);
                    }

                    // Sincronizar roles (elimina roles anteriores y asigna el nuevo)
                    $user->syncRoles([$request->role]);
                } else {
                    // Si se envía role como null, remover todos los roles
                    $user->syncRoles([]);
                }
            }

            // Recargar relaciones
            $user->load('roles', 'permissions');

            DB::commit();

            return UserResource::make($user)
                ->additional([
                    'success' => true,
                    'message' => 'Usuario actualizado exitosamente'
                ])
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);

        $user->delete();

        return UserResource::make($user)->response()->setStatusCode(204);
    }
}
