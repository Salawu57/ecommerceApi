<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use App\Traits\AdminAction;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use AdminAction;

    /**
     * Determine whether the user can view the model.
     */
    public function addCategory(User $user, Product $product): bool
    {
        return $user->id === $product->seller->id;
    }


    /**
     * Determine whether the user can update the model.
     */
    public function deleteCategory(User $user, Product $product): bool
    {
       return $user->id === $product->seller->id; 
    }

    
}
