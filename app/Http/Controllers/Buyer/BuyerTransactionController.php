<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class BuyerTransactionController extends ApiController implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public static function middleware(): array
    {
        return [
          new Middleware('scope:read-general', only:['index']),
        ];
    }
     
    public function index(Buyer $buyer)
    {
       $transactions = $buyer->transactions;

       return $this->showAll($transactions);
    }

}
