<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Vehiculo', 'Efectivo', 'Accesorio', 'Servicio', 'Electrónico', 'Tarjeta de Regalo'] as $name) {
            Type::firstOrCreate(['name' => $name]);
        }
    }
}
