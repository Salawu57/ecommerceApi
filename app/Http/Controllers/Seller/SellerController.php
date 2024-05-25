<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SellerController extends ApiController implements HasMiddleware
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
      
        $sellers = Seller::has('products')->get();

        return $this->showall($sellers);
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        return $this->showOne($seller);
    }

}
