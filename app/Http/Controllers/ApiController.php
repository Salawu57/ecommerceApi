<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ApiController extends Controller implements HasMiddleware
{
    use ApiResponser;

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }


   
}
