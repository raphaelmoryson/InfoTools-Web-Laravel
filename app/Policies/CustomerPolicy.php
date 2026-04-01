<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Tout utilisateur authentifié peut lister les clients.
     * (Le filtrage par commercial se fait dans le contrôleur/query scope.)
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Un responsable voit tous les clients.
     * Un commercial ne voit que ses propres clients.
     */
    public function view(User $user, Customer $customer): bool
    {
        return !$user->is_commercial || $customer->user_id === $user->id;
    }

    /**
     * Tout utilisateur authentifié peut créer un client.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Un responsable peut modifier tous les clients.
     * Un commercial ne modifie que ses propres clients.
     */
    public function update(User $user, Customer $customer): bool
    {
        return !$user->is_commercial || $customer->user_id === $user->id;
    }

    /**
     * Un responsable peut supprimer tous les clients.
     * Un commercial ne supprime que ses propres clients.
     */
    public function delete(User $user, Customer $customer): bool
    {
        return !$user->is_commercial || $customer->user_id === $user->id;
    }

    /**
     * Seul un responsable peut restaurer un client supprimé.
     */
    public function restore(User $user, Customer $customer): bool
    {
        return !$user->is_commercial;
    }

    /**
     * Seul un responsable peut supprimer définitivement un client.
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
        return !$user->is_commercial;
    }
}