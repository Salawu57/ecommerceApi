<?php

namespace App\Models;

use App\Models\Seller;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes; 
    
    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
       ];

    public function isAvailable(){

      return $this->status == PRODUCT::AVAILABLE_PRODUCT;
    }

    public function seller(){

        return $this->belongsTo(Seller::class);
    }

    public function transaction(){

        return $this->hasMany(Transaction::class);

    }

    public function categories(){
        
        return $this->belongsToMany(Category::class);
    }
    
}
