<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryProductController extends ApiController implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

     public static function middleware(): array
      {
          return [
              new Middleware('client.credentials', only: ['index']),
          
          ];
      }


    public function index(Category $category)
    {
       $products = $category->products;
       return $this->showAll($products);
    }

  
}
