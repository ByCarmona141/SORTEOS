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
        'opportunities' .
        'status',
        'draw_date',
        'reservation_expiration_hours',
        'draw_trigger_percentage',
        'created_by'
    ];
}
