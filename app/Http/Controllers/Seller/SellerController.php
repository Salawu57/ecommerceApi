<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{

    public function __construct(){ 

        self::middleware();
    
     }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        $sellers = Seller::has('products')->get();

        return $this->showall($sellers);
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        return $this->showOne($seller);
    }

}
