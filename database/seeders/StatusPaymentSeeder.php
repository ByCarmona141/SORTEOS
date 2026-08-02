<?php

namespace Database\Seeders;

use App\Models\StatusPayment;
use Illuminate\Database\Seeder;

class StatusPaymentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Pendiente', 'Validado', 'Rechazado'] as $name) {
            StatusPayment::firstOrCreate(['name' => $name]);
        }
    }
}
