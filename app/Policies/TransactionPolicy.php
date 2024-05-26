<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;
use App\Traits\AdminAction;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{

    use AdminAction;
    
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer->id || $user->id === $transaction->product->seller->id;
    }

   
}
