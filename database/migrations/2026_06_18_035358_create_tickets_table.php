<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA: tickets (Boletos)
 *
 * Esta es la tabla más crítica del sistema.
 * Va DESPUÉS de users, raffles y payments porque depende de las tres.
 *
 * ¿Qué representa cada fila?
 * Cada fila es UN boleto físico dentro de UN sorteo.
 * Si el sorteo tiene 2500 boletos, habrá 2500 filas con raffle_id = X.
 *
 * Decisiones importantes:
 *
 * 1. number como string (no integer):
 *    Los boletos se muestran con ceros a la izquierda: "0022", "0001", "2499".
 *    Si usas integer, pierdes los ceros: 22, 1, 2499.
 *    Con string mantienes el formato visual correcto.
 *    Longitud 10 es flexible para sorteos con más de 9999 boletos.
 *
 * 2. status como enum:
 *    - disponible: nadie lo ha seleccionado
 *    - apartado: cliente lo seleccionó pero NO ha pagado (temporal)
 *    - pagado: pago validado por admin/gerente
 *    - ganador: este boleto ganó el sorteo
 *    Estos estados son el corazón del flujo. Enum es perfecto aquí.
 *
 * 3. user_id nullable (nullOnDelete):
 *    - null = boleto disponible, nadie lo ha tomado
 *    - con valor = cliente que lo apartó o pagó
 *    Si el cliente se borra → boleto vuelve a disponible (user_id = null).
 *    Cuidado: en el modelo/servicio habría que limpiar el status también.
 *
 * 4. payment_id nullable (nullOnDelete):
 *    - null = no tiene pago asociado (disponible o apartado sin pago)
 *    - con valor = hay un comprobante de pago para este boleto
 *    Si el pago se borra → FK se vuelve null (no se borra el ticket).
 *
 * 5. reserved_at y paid_at:
 *    - reserved_at: cuándo fue apartado (para calcular expiración)
 *    - paid_at: cuándo se confirmó el pago
 *    Dos timestamps separados permiten calcular:
 *    a) ¿Cuánto tiempo llevó pagar desde que apartó?
 *    b) ¿Expiró? (ahora - reserved_at > expiration_hours)
 *
 * 6. UNIQUE en (raffle_id, number):
 *    CRÍTICO para alta concurrencia. Garantiza que NUNCA existan dos
 *    boletos con el mismo número en el mismo sorteo.
 *    Incluso si dos procesos intentan crear el mismo boleto simultáneamente,
 *    la base de datos rechazará el duplicado con un error de constraint.
 *    Esto es más confiable que validarlo solo en PHP.
 *
 * Índices compuestos:
 *
 * - (raffle_id, status): la query más común es
 *   "dame todos los boletos DISPONIBLES del sorteo X"
 *   Un índice compuesto es MÁS eficiente que dos índices separados
 *   para este tipo de consulta.
 *
 * - (raffle_id, number): para buscar un boleto específico
 *   "¿El boleto 0022 del sorteo 5 existe?"
 *   Esta es la misma combinación del UNIQUE, MySQL/PostgreSQL
 *   reutiliza ese índice automáticamente.
 *
 * - user_id: "todos los boletos de un cliente"
 *
 * - payment_id: "todos los boletos de un pago específico"
 *   Útil cuando el admin valida un pago y necesita marcar N boletos.
 *
 * ¿Por qué no guardamos las combinaciones (0022, 2522, 5022...)?
 * Porque se calculan en tiempo real con una fórmula simple:
 *   combinación = number + (n * ticket_count)
 * Guardarlas sería redundante y multiplicaría el tamaño de la tabla
 * por el número de oportunidades.
 */

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // Sorteo al que pertenece este boleto
            // cascadeOnDelete: si se borra el sorteo, se borran sus boletos
            $table->foreignId('raffle_id')
                ->constrained('raffles')
                ->cascadeOnDelete();

            // Cliente que apartó o pagó este boleto
            // null = boleto libre/disponible
            // nullOnDelete: si el cliente se borra, boleto queda disponible
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Pago asociado a este boleto
            // null = no tiene pago vinculado todavía
            // nullOnDelete: si el pago se borra, FK queda null
            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('payments')
                ->nullOnDelete();

            // Número del boleto con ceros a la izquierda ("0001", "2499")
            // String para preservar el formato visual
            $table->string('number', 10);

            // Estado en el ciclo de vida del boleto
            $table->enum('status', ['disponible', 'apartado', 'pagado', 'ganador'])
                ->default('disponible');

            // Cuándo fue apartado (para calcular si ya expiró la reserva)
            $table->timestamp('reserved_at')->nullable();

            // Cuándo se confirmó el pago (para historial)
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            // === RESTRICCIÓN DE UNICIDAD ===
            // Un número solo puede existir UNA VEZ por sorteo
            // Esta es la protección más importante contra duplicados
            // Funciona incluso bajo alta concurrencia a nivel de base de datos
            $table->unique(['raffle_id', 'number']);

            // === ÍNDICES COMPUESTOS ===

            // Query más común: "boletos disponibles del sorteo X"
            // El índice compuesto es más eficiente que dos índices separados
            $table->index(['raffle_id', 'status']);

            // Buscar un boleto por número en un sorteo
            // (Aprovecha también el índice UNIQUE, pero lo declaramos
            //  explícitamente para mayor claridad y compatibilidad)
            $table->index(['raffle_id', 'number']);

            // Todos los boletos de un cliente
            $table->index('user_id');

            // Todos los boletos de un pago (para validación masiva)
            $table->index('payment_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
