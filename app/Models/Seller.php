<?php

namespace App\Models;


use App\Models\Product;
use App\Models\Scopes\HasProductScope;
use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([HasProductScope::class])]
class Seller extends User
{
    public $transformer = SellerTransformer::class;
    use HasFactory;

    public function products(){

        return $this->hasMany(Product::class);
 
    }
}
