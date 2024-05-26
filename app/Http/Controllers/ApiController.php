<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Auth\Access\AuthorizationException;

class ApiController extends Controller implements HasMiddleware
{
    use ApiResponser;
    

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }


    protected function allowedAdminAction(){
         
        if(Gate::denies('admin-action')){
            throw new AuthorizationException('This action is unauthorized');
        }
    }


}
