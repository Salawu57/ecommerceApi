<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Buyer;
use App\Traits\AdminAction;
use Illuminate\Auth\Access\Response;

class BuyerPolicy
{
    use AdminAction;
    /**
     * Determine whether the user can view the model.
     */
 

    public function view(User $user, Buyer $buyer): bool
    {
        return $user->id === $buyer->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function purchase(User $user, Buyer $buyer): bool
    {
        return $user->id === $buyer->id;
    }

    
}
