<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */

     public function __construct(){ 

        self::middleware();
    
     }

     
    public function index(Category $category)
    {
      $this->allowedAdminAction();

       $products = $category->products()
       ->with('seller')
       ->get()
       ->pluck('seller')
       ->unique()
       ->values();
       
       return $this->showAll($products);
    }

  
}
