<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA: raffles (Sorteos)
 *
 * Decisiones importantes:
 *
 * 1. ticket_count (int, no bigInt):
 *    Los sorteos raramente superan los 100,000 boletos. Un int es suficiente
 *    y ocupa menos espacio. Si en el futuro se necesita más, se puede migrar.
 *
 * 2. ticket_price decimal(10,2):
 *    Para dinero SIEMPRE decimal, nunca float/double.
 *
 * 3. opportunities (tinyInteger sin signo):
 *    Las oportunidades de participación raramente pasan de 10-20.
 *    tinyInteger (0-255) es suficiente y ahorra espacio.
 *
 * 4. status como catalogo:
 *    Para este caso, draft = en preparación, active = en venta, finished = cerrado
 *
 * 5. reservation_expiration_hours (nullable tinyInteger):
 *    Si es null → se usa el valor del .env (configuración global).
 *    Si tiene valor → sobreescribe el .env solo para este sorteo.
 *
 * 6. draw_trigger_percentage (unsignedTinyInteger, default null):
 *    El porcentaje de boletos PAGADOS necesario para que el sorteo
 *    pueda realizarse. Ejemplo: 80 significa que con el 80% vendido
 *    ya se puede llevar a cabo el sorteo.
 *
 *    ¿Por qué nullable y no con default fijo?
 *    - null → se usa el valor global definido en .env
 *             (RAFFLE_DRAW_TRIGGER_PERCENTAGE=80)
 *    - con valor → sobreescribe solo para este sorteo
 *    Este patrón es el mismo que reservation_expiration_hours:
 *    configuración global con override por sorteo.
 *
 *    ¿Por qué tinyInteger (0-100)?
 *    Un porcentaje siempre vive entre 0 y 100.
 *    tinyInteger sin signo aguanta 0-255, más que suficiente.
 *    Ocupa 1 byte vs 4 bytes de un integer normal.
 *
 *    ¿Por qué no guardarlo solo en .env?
 *    Porque a veces un sorteo especial necesita arrancar con el 60%
 *    y otro necesita el 90%. Si solo existe en .env, todos los sorteos
 *    activos heredan el mismo valor y no puedes personalizar.
 *
 * 7. created_by (FK a users, nullOnDelete):
 *    Si el admin que creó el sorteo es eliminado, el sorteo no desaparece.
 *    Se vuelve null, lo que es razonable (el sorteo sigue existiendo).
 *
 * Índices:
 * - status: se filtra frecuentemente por estado ('active')
 * - draw_date: para mostrar próximos sorteos ordenados por fecha
 */

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();

            // Información del sorteo
            $table->string('name');
            $table->text('description')->nullable();

            // Configuración de boletos
            // int sin signo = hasta 4,294,967,295 boletos (más que suficiente)
            $table->unsignedInteger('ticket_count');

            // Precio por boleto con precisión exacta para dinero
            $table->decimal('ticket_price', 10, 2);

            // Cuántas veces participa cada boleto (ej: 4 oportunidades)
            // tinyInteger: 0-255, suficiente para oportunidades
            $table->unsignedTinyInteger('opportunities')->default(1);

            // Estado del ciclo de vida del sorteo
            // draft = en preparación, active = en venta, finished = cerrado
            $table->foreignId('status_id')
                ->constrained('statuses')
                ->nullableOnDelete();

            // Fecha y hora del sorteo (puede no estar definida aún)
            $table->dateTime('draw_date')->nullable();

            // Horas de reserva antes de expirar (null = usa config .env)
            $table->unsignedTinyInteger('reservation_expiration_hours')->nullable();

            // Porcentaje mínimo de boletos PAGADOS para poder realizar el sorteo
            // null  → usa RAFFLE_DRAW_TRIGGER_PERCENTAGE del .env (ej: 80)
            // valor → sobreescribe solo para este sorteo (ej: 60, 90, 100)
            // Rango válido: 1-100
            $table->unsignedTinyInteger('draw_trigger_percentage')->nullable();

            // Auditoría: quién creó el sorteo
            // nullOnDelete: si el admin se borra, el sorteo no se borra
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // === ÍNDICES ===
            // Ordenar/filtrar por fecha del sorteo
            $table->index('draw_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raffles');
    }
};
