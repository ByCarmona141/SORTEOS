<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'raffle_id',
        'total_amount',
        'payment_method_id',
        'reference',
        'proof_image',
        'status_payment_id',
        'validated_by',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'validated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function statusPayment()
    {
        return $this->belongsTo(StatusPayment::class);
    }

    // Quién aprobó/rechazó el pago (admin o gerente)
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Mismo patrón que Raffle::statusBadgeMap(), para no repetir
    // colores e íconos en cada vista.
    public static function statusBadgeMap(): array
    {
        return [
            'pendiente' => ['icon' => 'schedule', 'classes' => 'bg-primary/10 text-primary border-primary/30'],
            'validado'  => ['icon' => 'check_circle', 'classes' => 'bg-emerald-400/10 text-emerald-400 border-emerald-400/30'],
            'rechazado' => ['icon' => 'cancel', 'classes' => 'bg-error/10 text-error border-error/30'],
        ];
    }

    public function statusBadge(): array
    {
        $map = self::statusBadgeMap();
        $name = strtolower($this->statusPayment->name ?? 'pendiente');

        return $map[$name] ?? ['icon' => 'help', 'classes' => 'bg-on-surface-variant/10 text-on-surface-variant border-outline-variant/40'];
    }

    /**
     * Nos dice si el comprobante guardado es un PDF,
     * para saber si mostrarlo con <img> o con <iframe>.
     */
    public function proofIsPdf(): bool
    {
        return $this->proof_image && str_ends_with(strtolower($this->proof_image), '.pdf');
    }
}
