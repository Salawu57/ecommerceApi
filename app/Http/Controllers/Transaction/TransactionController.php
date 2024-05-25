<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TransactionController extends ApiController implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
          new Middleware('scope:read-general', only:['show']),
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::all();

        return $this->showAll($transactions);
    }

   
    public function show(Transaction $transaction)
    {
        return $this->showOne($transaction);
    }
}