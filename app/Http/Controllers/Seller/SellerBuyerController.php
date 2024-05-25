<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class SellerBuyerController extends ApiController
{

    
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        $buyer = $seller->products()
        ->whereHas('transaction')
        ->with('transaction.buyer')
        ->get()
        ->pluck('transaction')
        ->collapse()
        ->pluck('buyer')
        ->unique('id')
        ->values();

        return $this->showAll($buyer);
    }

   
}
