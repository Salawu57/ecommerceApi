<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SellerCategoryController extends ApiController implements HasMiddleware
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
       
          $categories = $seller->products()
          ->whereHas('categories')
          ->with('categories')
          ->get()
          ->pluck('categories')
          ->collapse()
          ->unique('id')
          ->values();
          
          return $this->showAll($categories);
    
    }


}
