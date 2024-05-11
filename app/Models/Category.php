<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes; 

    protected $dates = ['deleted_at']; 
    
    protected $fillable = [
     'name',
     'description',
    ];


    public function products(){
        return $this->belongsToMany(Product::class);
    }
}