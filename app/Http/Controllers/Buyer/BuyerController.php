<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $buyers = Buyer::has('transactions')->get();
          return $this->showall($buyers);
    }

    
    
    public function show(Buyer $buyer)
    {
      //  $buyer = Buyer::has('transactions')->findOrFail($id);
       return $this->showOne($buyer);
    }

   
}
