<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SellerTransactionController extends ApiController implements HasMiddleware
{
  
  public static function middleware(): array
  {
      return [
        new Middleware('scope:read-general', only:['index']),
        new Middleware('can:view,seller', only:['index']),
    
      ];
  }

    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
      $transactions = $seller->products()
      ->whereHas('transaction')
      ->get()
      ->pluck('transaction')
      ->collapse();
      
      return $this->showAll($transactions);
    }

}
