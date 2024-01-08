<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('list.shops');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shop $shop): bool
    {
        return $user->can('view.shop');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.shop');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shop $shop): bool
    {
        return $user->can('update.shop');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shop $shop): bool
    {
        return $user->can('delete.shop');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Shop $shop): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Shop $shop): bool
    {
        //
    }

    public function logo(): bool
    {
        return false;
    }
    
    public function activateShop(): bool
    {
        return false;
    }
    
    public function deactivateShop(): bool
    {
        return false;
    }

    public function shopCount(): bool
    {
        return false;
    }

    public function shopCountByCategory(User $user): bool
    {
        return false;
    }

    public function shopProducts(User $user): bool
    {
        return false;
    }
}
