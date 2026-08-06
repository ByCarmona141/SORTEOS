<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'raffle_id',
        'user_id',
        'payment_id',
        'number',
        'status_ticket_id',
        'reserved_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'reserved_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function statusTicket()
    {
        return $this->belongsTo(StatusTicket::class);
    }

    public static function statusBadgeMap(): array
    {
        return [
            'Disponible' => ['classes' => 'bg-emerald-400/10 border-emerald-400/30 text-emerald-400', 'dot' => 'bg-emerald-400'],
            'Apartado'   => ['classes' => 'bg-yellow-400/10 border-yellow-400/30 text-yellow-400', 'dot' => 'bg-yellow-400'],
            'Pagado'     => ['classes' => 'bg-primary/10 border-primary/30 text-primary', 'dot' => 'bg-primary'],
            'Ganador'    => ['classes' => 'bg-tertiary/10 border-tertiary/30 text-tertiary', 'dot' => 'bg-tertiary'],
        ];
    }

    public function statusBadge(): array
    {
        $map = self::statusBadgeMap();
        $name = $this->statusTicket->name ?? 'Disponible';

        return $map[$name] ?? ['classes' => 'bg-on-surface-variant/10 border-outline-variant/40 text-on-surface-variant', 'dot' => 'bg-on-surface-variant'];
    }
}
