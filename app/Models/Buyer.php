<?php

namespace App\Models;

use App\Models\Transaction;
use App\Transformers\BuyerTransformer;
use App\Models\Scopes\TransactionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([TransactionScope::class])]

class Buyer extends User
{
    use HasFactory;

    public $transformer = BuyerTransformer::class;

    public function transactions(){

        return $this->hasMany(Transaction::class);
    }
}
