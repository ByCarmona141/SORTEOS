<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    protected $fillable = [
        'raffle_id',
        'ticket_id',
        'type_id',
        'position',
        'title',
        'description',
        'amount',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
