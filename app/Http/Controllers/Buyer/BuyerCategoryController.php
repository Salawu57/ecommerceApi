<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
        ->get()
        ->pluck('product.categories')
        ->collapse() // collapse to return single collection;
        ->unique('id')
        ->values(); ///re-index the collection to avoid space inbtw the list

        return $this->showAll($categories);
    }

   
}
