<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-permissions',
            'view-permissions',
            'update-permissions',
            'delete-permissions',
            'create-roles',
            'view-roles',
            'update-roles',
            'delete-roles',
            'create-users',
            'view-users',
            'update-users',
            'delete-users',
            'create-payments',
            'view-payments',
            'update-payments',
            'delete-payments',
            'create-prizes',
            'view-prizes',
            'update-prizes',
            'delete-prizes',
            'create-raffles',
            'view-raffles',
            'update-raffles',
            'delete-raffles',
            'create-tickets',
            'view-tickets',
            'update-tickets',
            'delete-tickets',
        ];

        // Crear permisos
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
