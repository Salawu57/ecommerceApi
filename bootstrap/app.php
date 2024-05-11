<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e) {

            if($e->getPrevious() instanceof ModelNotFoundException){

                $modelNames = class_basename($e->getPrevious()->getModel());

                return response()->json(['error'=> "{$modelNames} does not exists", 'code' => 404], 404);
            }
           
            return response()->json(['error'=> 'This specified URL cannot be found.', 'code' => 404], 404);
        });


        $exceptions->render(function (ValidationException $e) {

        $errors = $e->validator->errors()->getMessages();  

        return response()->json(['error'=> $errors, 'code' => 422], 422);

        });

        $exceptions->render(function (AuthenticationException $e) {

            return response()->json(['error'=> 'Unauthenticated.', 'code' => 401], 401);
    
        });
        
        $exceptions->render(function (AuthorizationException $e) {

            return response()->json(['error'=> $e->getMessage(), 'code' => 403], 403);
    
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e) {

            return response()->json(['error'=> 'Method not allowed on this request', 'code' => 405], 405);
    
        });

        $exceptions->render(function (HttpException $e) {

            return response()->json(['error'=> $e->getMessage(), 'code' => $e->getCode()], $e->getCode());
    
        });

        $exceptions->render(function (QueryException $e) {
        
        
            if(config('app.debug')){
                
                return response()->json(['error'=> $e->getMessage(), 'code' => $e->getCode()]);
            }
            return response()->json(['error'=> 'Opps Something went wrong. Please try again later.', 'code' => 500], 500);
    
        });
        
    })->create();


  