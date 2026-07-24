<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'ticket_count',
        'ticket_price',
        'opportunities',
        'status_id',
        'draw_date',
        'reservation_expiration_hours',
        'draw_trigger_percentage',
        'created_by'
    ];

    protected function casts(): array
    {
        return [
            'draw_date' => 'datetime',
        ];
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
