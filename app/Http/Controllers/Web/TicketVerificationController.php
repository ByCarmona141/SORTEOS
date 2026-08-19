<?php

namespace App\Http\Controllers\Web;

use App\Models\Ticket;
use App\Http\Controllers\Controller;

class TicketVerificationController extends Controller
{
    /**
     * Página pública (sin login) para verificar que un boleto es legítimo.
     * Solo se puede acceder con la URL firmada que genera el sistema al
     * crear el PDF, por eso no necesita autenticación ni políticas de acceso.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('raffle', 'statusTicket', 'user');

        // Revisamos si este boleto es el ganador de algún premio del sorteo
        $prizeWon = $ticket->raffle->prizes()
            ->where('ticket_id', $ticket->id)
            ->first();

        return view('ticket.verify', compact('ticket', 'prizeWon'));
    }
}