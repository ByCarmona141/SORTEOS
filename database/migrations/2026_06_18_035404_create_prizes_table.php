<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA: prizes (Premios)
 *
 * ¿Por qué al final?
 * Prizes solo depende de raffles. Podría ir antes de tickets y payments,
 * pero colocarlo al final es una convención: va de más crítico (tickets)
 * a menos crítico (premios). El orden no afecta la integridad aquí.
 *
 * ¿Qué representa cada fila?
 * Cada fila es UN premio dentro de UN sorteo.
 * Un sorteo puede tener varios premios (1er lugar, 2do lugar, etc.)
 *
 * Decisiones importantes:
 *
 * 1. position (unsignedTinyInteger):
 *    El lugar del premio (1, 2, 3...).
 *    tinyInteger: 0-255, raramente hay más de 10 premios en una rifa.
 *    Ahorra espacio comparado con int o bigInt.
 *
 * 2. UNIQUE en (raffle_id, position):
 *    Un sorteo no puede tener dos premios con la misma posición.
 *    No puede existir dos "1er lugar" en el mismo sorteo.
 *
 * 3. type como Catalogo:
 *    Los tipos de premio son muy variados: "efectivo", "automóvil",
 *    "viaje", "electrodoméstico", "terreno"...
 *
 * 4. amount (nullable decimal):
 *    Solo aplica para premios en efectivo.
 *    Si el premio es un auto, amount sería null.
 *    Si es efectivo de $50,000, amount = 50000.00
 *
 * 5. image_path (nullable string):
 *    La imagen del premio (foto del auto, casa, etc.)
 *    Mismo principio que proof_image: guardamos la RUTA, no la imagen.
 *    Nullable porque no siempre hay foto disponible al crear el premio.
 *
 * 6. cascadeOnDelete en raffle_id:
 *    Si se borra el sorteo, sus premios también se borran.
 *    Tiene sentido: los premios sin sorteo no tienen utilidad.
 *
 * Índices:
 * - raffle_id: listar todos los premios de un sorteo
 * - type: podría usarse para estadísticas ("¿cuántos premios en efectivo?")
 */

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();

            // Sorteo al que pertenece este premio
            // Si se borra el sorteo, sus premios también desaparecen
            $table->foreignId('raffle_id')
                ->constrained('raffles')
                ->cascadeOnDelete();

            //Para saber el boleto ganador
            $table->foreignId('ticket_id')
                ->nullable()
                ->constrained('tickets')
                ->cascadeOnDelete();

            // Categoría del premio (efectivo, automóvil, viaje, etc.)
            $table->foreignId('type_id')
                ->constrained('types')
                ->cascadeOnDelete();

            // Posición del premio (1er lugar, 2do lugar, etc.)
            $table->unsignedTinyInteger('position');

            // Nombre del premio (ej: "Automóvil 2025", "50,000 pesos")
            $table->string('title');

            // Descripción detallada del premio
            $table->text('description')->nullable();

            // Valor económico (solo para premios en efectivo)
            // null = no aplica (ej: automóvil cuyo valor no se especifica)
            $table->decimal('amount', 10, 2)->nullable();

            // Ruta de la imagen del premio en storage
            // null = sin imagen todavía
            $table->string('image_path')->nullable();

            $table->timestamps();

            // === RESTRICCIÓN DE UNICIDAD ===
            // No puede haber dos premios con la misma posición en el mismo sorteo
            $table->unique(['raffle_id', 'position']);

            // === ÍNDICES ===

            // Listar premios de un sorteo específico
            $table->index('raffle_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
