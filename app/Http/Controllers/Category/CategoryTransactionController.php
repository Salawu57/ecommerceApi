<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */

     public function __construct(){ 

        self::middleware();
    
     }

     
    public function index(Category $category)
    {

     $this->allowedAdminAction();

       $transactions = $category->products()
       ->whereHas('transaction')
       ->with('transaction')
       ->get()
       ->pluck('transaction')
       ->collapse();
       
       return $this->showAll($transactions);
    }

   
}
