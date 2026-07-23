<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Statuses = [
            ['name' => 'Pendiente'], //Creado pero los cliente no pueden ver el sorteo.
            ['name' => 'Activo'],
            ['name' => 'Agotado'], //Se vendieron todos los boletos.
            ['name' => 'En espera de sorteo'], //Llegó la fecha limite de compra.
            ['name' => 'Finalizado'], //Ya hay ganador :D
            ['name' => 'Suspendido'], //En pausa temporalmente, con probabilidad de reanudar.
            ['name' => 'Cancelado'],
        ];

        foreach ($Statuses as $status) {
            Status::create($status);
        }
    }
}
