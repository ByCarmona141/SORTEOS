<?php

namespace App\Http\Controllers\Web;

use App\Models\Ticket;
use App\Models\Raffle;
use App\Models\StatusTicket;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    /**
     * Muestra la cuadrícula de boletos de un sorteo.
     */
    public function index(Request $request, Raffle $raffle)
    {
        $query = $raffle->tickets()->with('statusTicket');

        if ($request->filled('status')) {
            $query->whereHas('statusTicket', fn ($q) => $q->where('name', $request->status));
        }

        if ($request->filled('search')) {
            $query->where('number', 'like', "%{$request->search}%");
        }

        $tickets = $query->orderBy('number')->paginate(500)->withQueryString();

        $statusTickets = StatusTicket::all();

        // Conteo por estado, para las tarjetitas de resumen arriba
        $counts = $raffle->tickets()
            ->join('status_tickets', 'tickets.status_ticket_id', '=', 'status_tickets.id')
            ->selectRaw('status_tickets.name, count(*) as total')
            ->groupBy('status_tickets.name')
            ->pluck('total', 'name');

        return view('ticket.index', compact('raffle', 'tickets', 'statusTickets', 'counts'));
    }

    /**
     * Genera los boletos de un sorteo según ticket_count.
     * Si ya existen boletos, no vuelve a crearlos (evita duplicados).
     */
    public function generate(Raffle $raffle)
    {
        if ($raffle->tickets()->exists()) {
            return back()->with('error', 'Este sorteo ya tiene boletos generados.');
        }

        $disponible = StatusTicket::where('name', 'Disponible')->first();
        $padLength = strlen((string) $raffle->ticket_count); // ej. 1000 boletos -> 4 dígitos
        $now = now();

        DB::transaction(function () use ($raffle, $disponible, $padLength, $now) {
            $batch = [];

            for ($i = 1; $i <= $raffle->ticket_count; $i++) {
                $batch[] = [
                    'raffle_id' => $raffle->id,
                    'number' => str_pad($i, $padLength, '0', STR_PAD_LEFT),
                    'status_ticket_id' => $disponible?->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Metemos los boletos en bloques de 500 en vez de uno por uno,
                // así no se satura la memoria si el sorteo tiene miles de boletos
                if (count($batch) === 500) {
                    Ticket::insert($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                Ticket::insert($batch);
            }
        });

        return redirect()
            ->route('raffle.tickets.index', $raffle)
            ->with('success', 'Se generaron ' . $raffle->ticket_count . ' boletos correctamente.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
