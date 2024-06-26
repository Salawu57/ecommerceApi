<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ProductTransactionController extends ApiController
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

        $transactions = $product->transaction;
        return $this->showAll($transactions);
    }

  
}
