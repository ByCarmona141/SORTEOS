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

    public function prizes()
    {
        return $this->hasMany(Prize::class);
    }

    public static function statusBadgeMap(): array
    {
        return [
            'Pendiente'           => ['icon' => 'schedule',       'classes' => 'bg-on-surface-variant/10 text-on-surface-variant border-outline-variant/40'],
            'Activo'              => ['icon' => 'check_circle',   'classes' => 'bg-primary/10 text-primary border-primary/30'],
            'Agotado'             => ['icon' => 'inventory_2',    'classes' => 'bg-orange-400/10 text-orange-400 border-orange-400/30'],
            'En espera de sorteo' => ['icon' => 'hourglass_top',  'classes' => 'bg-tertiary/10 text-tertiary border-tertiary/30'],
            'Finalizado'          => ['icon' => 'flag',           'classes' => 'bg-secondary/10 text-secondary border-secondary/30'],
            'Suspendido'          => ['icon' => 'pause_circle',   'classes' => 'bg-yellow-400/10 text-yellow-400 border-yellow-400/30'],
            'Cancelado'           => ['icon' => 'cancel',         'classes' => 'bg-error/10 text-error border-error/30'],
        ];
    }

    public function statusBadge(): array
    {
        $map = self::statusBadgeMap();
        $name = $this->status->name ?? 'Pendiente';

        return $map[$name] ?? ['icon' => 'help', 'classes' => 'bg-on-surface-variant/10 text-on-surface-variant border-outline-variant/40'];
    }
}
