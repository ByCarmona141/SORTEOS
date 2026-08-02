<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;

class PaymentPolicy
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
     * Cualquier usuario logueado puede entrar a la lista de pagos.
     * El controlador decide si ve TODOS los pagos o solo los suyos.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Ver un pago específico: o tiene el permiso de revisor,
     * o el pago es suyo.
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->can('view-payments') || $payment->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-payments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $model): bool
    {
        return $user->can('update-payments');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $model): bool
    {
        return $user->can('delete-payments');
    }

    /**
     * Aprobar/rechazar usa el mismo permiso que editar
     */
    public function review(User $user, Payment $payment): bool
    {
        return $user->can('update-payments');
    }
}
