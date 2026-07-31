<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;

class TicketPolicy
{
    /**
     * Aplica antes de los demás métodos.
     * Permite que el 'admin' o el superusuario salte las verificaciones.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null; // Si no es Admin, continúa al método viewAny
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $model): bool
    {
        return $user->can('view-tickets');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-tickets');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $model): bool
    {
        return $user->can('update-tickets');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $model): bool
    {
        return $user->can('delete-tickets');
    }
}
