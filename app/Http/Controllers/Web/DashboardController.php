<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller {
    public function index() {
                // Traemos los 3 sorteos más recientes, junto con su estado y su
        // primer premio (para la miniatura) y contamos cuántos boletos
        // de cada uno ya están "Pagado" o "Ganador" para dibujar la barra
        // de progreso.
        $raffles = Raffle::with(['status', 'prizes'])
            ->withCount(['tickets as tickets_sold_count' => function ($query) {
                $query->whereHas('statusTicket', function ($q) {
                    $q->whereIn('name', ['Pagado', 'Ganador']);
                });
            }])
            ->latest()
            ->take(3)
            ->get();

        // Números para las 4 tarjetas de arriba (KPIs)
        $stats = [
            'revenue' => Payment::whereHas('statusPayment', fn ($q) => $q->where('name', 'Validado'))
                ->sum('total_amount'),
            'active_raffles' => Raffle::whereHas('status', fn ($q) => $q->where('name', 'Activo'))->count(),
            'tickets_sold' => Ticket::whereHas('statusTicket', fn ($q) => $q->whereIn('name', ['Pagado', 'Ganador']))->count(),
            'new_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        // Los últimos 5 pagos registrados, para la columna de "Actividad reciente"
        $recentPayments = Payment::with(['user', 'raffle'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('raffles', 'stats', 'recentPayments'));

    }
}
