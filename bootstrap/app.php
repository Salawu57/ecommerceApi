<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Middleware\TransformInput;
use Illuminate\Database\QueryException;
use App\Http\Middleware\SignatureMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Http\Middleware\CheckScopes;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Passport\Http\Middleware\CheckForAnyScope;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;
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
        $middleware->alias([
           'signature' => SignatureMiddleware::class,
           'transform.input' => TransformInput::class,
           'client.credentials' => CheckClientCredentials::class,
           'scope' => CheckForAnyScope::class,
           'scopes' => CheckScopes::class,
           
        ]);

       
        $middleware->web(append: [
            'signature:X-Application-Name',
        ]);

        $middleware->api(append: [
            'signature:X-Application-Name',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e) {

            if($e->getPrevious() instanceof ModelNotFoundException){

                $modelNames = class_basename($e->getPrevious()->getModel());

                return response()->json(['error'=> "{$modelNames} does not exists", 'code' => 404], 404);
            }
           
            return response()->json(['error'=> 'This specified URL cannot be found.', 'code' => 404], 404);
        });


        $exceptions->render(function (ValidationException $e, Request $request) {

            $errors = $e->validator->errors()->getMessages();  

            if ($request->is('api/*')) {

                return response()->json(['error'=> $errors, 'code' => 422], 422);
               
            }

            return $request->ajax() ? response()->json($errors, 422) : redirect()->back()->withInput($request->input())->withErrors($errors);
    
        });



        $exceptions->render(function (AuthenticationException $e, Request $request) {

            if ($request->is('api/*')) {

                return response()->json(['error'=> 'Unauthenticated.', 'code' => 401], 401);
            }

              return redirect()->guest('login'); 
    
        });
        
        $exceptions->render(function (AuthorizationException $e) {

            return response()->json(['error'=> $e->getMessage(), 'code' => 403], 403);
    
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e) {

            return response()->json(['error'=> 'Method not allowed on this request', 'code' => 405], 405);
    
        });

        $exceptions->render(function (HttpException $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json(['error'=> $e->getMessage(), 'code' => 422], 422);
            }

            if($e->getPrevious() instanceof TokenMismatchException){

               return redirect()->back()->withInput($request->input()); 

            }

             
        });

        $exceptions->render(function (QueryException $e) {
        
        
            if(config('app.debug')){
                
                return response()->json(['error'=> $e->getMessage(), 'code' => $e->getCode()]);
            }
            return response()->json(['error'=> 'Opps Something went wrong. Please try again later.', 'code' => 500], 500);
    
        });
        
    })->create();


  