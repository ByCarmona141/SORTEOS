<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait Sortable {
    /**
     * Aplica ordenamiento a una consulta, validando que la columna
     * pedida esté en la lista de columnas permitidas.
     *
     * @param  Builder  $query       La consulta (ej. Raffle::query())
     * @param  Request  $request     El request actual
     * @param  array    $sortable    Columnas permitidas para ordenar (ej. ['name', 'created_at'])
     * @param  string   $default     Columna por defecto si no viene ninguna en la URL
     */
    protected function applySorting(Builder $query, Request $request, array $sortable, string $default = 'created_at'): Builder
    {
        $sort = in_array($request->sort, $sortable) ? $request->sort : $default;
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction);
    }

    /**
     * Calcula cuántos resultados mostrar por página, validando
     * que el valor pedido esté entre las opciones permitidas.
     *
     * @param  Request  $request
     * @param  array    $allowed    Opciones válidas (ej. [10, 15, 25])
     * @param  int      $default    Valor si no viene ninguno en la URL
     */
    protected function resolvePerPage(Request $request, array $allowed = [10, 15, 25], int $default = 10): int
    {
        $requested = (int) $request->per_page;

        return in_array($requested, $allowed) ? $requested : $default;
    }
}
