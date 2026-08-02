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
            'Manager'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Admin: todos los permisos
        $adminRole = Role::where('name', 'Admin')->first();
        $adminRole->givePermissionTo(Permission::all());

        // Gerente: puede revisar pagos y sorteos, pero no eliminar ni administrar usuarios/roles
        $managerRole = Role::where('name', 'Manager')->first();
        $managerRole->givePermissionTo([
            'view-payments',
            'update-payments',
            'view-raffles',
            'view-tickets',
            'update-tickets',
        ]);
    }
}
