<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Seller;
use App\Traits\AdminAction;
use Illuminate\Auth\Access\Response;

class SellerPolicy
{
   
    use AdminAction;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function sale(User $user, User $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function editProduct(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteProduct(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }

}
