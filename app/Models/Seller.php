<?php

namespace App\Models;


use App\Models\Product;
use App\Models\Scopes\HasProductScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([HasProductScope::class])]
class Seller extends User
{
    use HasFactory;

    public function products(){

        return $this->hasMany(Product::class);
 
    }
}
