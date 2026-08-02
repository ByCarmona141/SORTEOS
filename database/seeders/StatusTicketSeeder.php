<?php

namespace Database\Seeders;

use App\Models\StatusTicket;
use Illuminate\Database\Seeder;

class StatusTicketSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Disponible', 'Apartado', 'Pagado', 'Ganador'] as $name) {
            StatusTicket::firstOrCreate(['name' => $name]);
        }
    }
}
