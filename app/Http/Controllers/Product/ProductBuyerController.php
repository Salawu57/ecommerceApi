<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ProductBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(){ 

        self::middleware();
    
     }
     
    public function index(Product $product)
    {
        $this->allowedAdminAction();

       $buyers = $product->transaction()
               ->with('buyer')
               ->get()
               ->pluck('buyer')
               ->unique('id')
               ->values();
        return $this->showAll($buyers);
    }

  
}
