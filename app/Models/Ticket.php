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
}
