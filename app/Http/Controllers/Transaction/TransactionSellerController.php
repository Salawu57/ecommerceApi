<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TransactionSellerController extends ApiController implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
          new Middleware('scope:read-general', only:['index']),
          new Middleware('can:view,transaction', only:['index']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Transaction $transaction)
    {
        $seller = $transaction->product->seller;
        return $this->showOne($seller);
    }

 
}
