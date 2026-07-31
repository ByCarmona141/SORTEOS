<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'User',
            'Client',
        ];

        // Crear roles
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Asignar todos los permisos al rol Admin
        $role = Role::find(1);
        $permissions = Permission::all();
        $role->givePermissionTo($permissions);
    }
}
