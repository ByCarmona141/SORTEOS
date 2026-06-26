<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA: payments (Pagos / Comprobantes)
 *
 * ¿Por qué payments ANTES que tickets?
 * Los tickets tienen una columna `payment_id` (FK a payments).
 * Si payments no existe primero, esa clave foránea falla.
 * El orden correcto es: users → raffles → payments → tickets
 *
 * ¿Qué representa esta tabla?
 * Cada fila es un comprobante de pago que el cliente sube.
 * Un pago puede cubrir MÚLTIPLES boletos (relación 1:N con tickets).
 *
 * Decisiones importantes:
 *
 * 1. proof_image como string (ruta):
 *    NUNCA guardes imágenes en la base de datos. Razones:
 *    - Las imágenes pueden pesar varios MB
 *    - Hacer backup de la DB se vuelve enorme
 *    - Las consultas se vuelven lentas
 *    - No puedes usar CDN
 *    En cambio, guardas la RUTA del archivo (ej: "comprobantes/uuid.jpg")
 *    y el archivo real está en storage/ o en S3/Cloudflare.
 *
 * 2. total_amount decimal(10,2):
 *    Mismo razonamiento que en raffles: dinero = siempre decimal.
 *
 * 3. payment_method como string (no enum):
 *    Los métodos de pago cambian frecuentemente (agregar Clip, CoDi, etc.)
 *    Con string no necesitas alterar la tabla para agregar uno nuevo.
 *    En el modelo puedes tener constantes o en config/ puedes listar los permitidos.
 *
 * 4. reference (nullable string):
 *    El número de referencia bancaria o folio de transferencia.
 *    No siempre aplica (ej: efectivo no tiene referencia), por eso nullable.
 *
 * 5. status como enum:
 *    - pendiente: recién subido, esperando revisión
 *    - validado: admin/gerente confirmó el pago
 *    - rechazado: admin/gerente rechazó el pago
 *    Estos 3 estados son estables, enum es apropiado.
 *
 * 6. validated_by (FK nullable a users):
 *    AUDITORÍA CRÍTICA: saber quién aprobó o rechazó.
 *    - nullOnDelete: si el admin se borra, el pago no desaparece.
 *    - Si es null = aún no ha sido revisado (pendiente).
 *
 * 7. validated_at (nullable timestamp):
 *    El CUÁNDO de la validación. Junto con validated_by dan auditoría completa.
 *    Ejemplo: "El gerente Juan lo aprobó el 15/junio/2025 a las 14:32"
 *
 * Índices:
 * - user_id: buscar todos los pagos de un cliente
 * - raffle_id: buscar pagos de un sorteo específico
 * - status: filtrar pagos pendientes de revisión (la query más común del admin)
 * - validated_by: ver qué aprobó cada admin/gerente
 */


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Cliente que realiza el pago
            // nullableOnDelete: si el cliente se borra, conservamos el historial
            $table->foreignId('user_id')
                ->constrained('users')
                ->nullableOnDelete();

            // Sorteo al que corresponde este pago
            // cascadeOnDelete: si se borra el sorteo, los pagos también (tiene sentido)
            $table->foreignId('raffle_id')
                ->constrained('raffles')
                ->cascadeOnDelete();

            // Total pagado (puede cubrir múltiples boletos)
            $table->decimal('total_amount', 10, 2);

            // Método de pago (transferencia, efectivo, CoDi, etc.)
            // String para flexibilidad futura
            $table->string('payment_method');

            // Número de referencia bancaria (nullable: efectivo no tiene)
            $table->string('reference')->nullable();

            // Ruta del comprobante en storage (NUNCA la imagen en sí)
            // Ejemplo: "comprobantes/2025/06/uuid.jpg"
            $table->string('proof_image');

            // Estado del pago en el flujo de validación
            $table->enum('status', ['pendiente', 'validado', 'rechazado'])
                ->default('pendiente');

            // === AUDITORÍA DE VALIDACIÓN ===
            // Quién revisó el pago (admin o gerente)
            // null = nadie lo ha revisado todavía
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Cuándo se revisó el pago
            // null = todavía no ha sido revisado
            $table->timestamp('validated_at')->nullable();

            $table->timestamps();

            // === ÍNDICES ===

            // Todos los pagos de un cliente específico
            $table->index('user_id');

            // Todos los pagos de un sorteo específico
            $table->index('raffle_id');

            // Filtrar por estado (admin ve "todos los pendientes")
            $table->index('status');

            // Ver historial de validaciones por admin/gerente
            $table->index('validated_by');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
