<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class TransactionController extends ApiController
{

    public function __construct(){ 

        self::middleware();
    
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