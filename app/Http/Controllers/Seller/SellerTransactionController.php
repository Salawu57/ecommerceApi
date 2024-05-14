<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class SellerTransactionController extends ApiController
{
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
