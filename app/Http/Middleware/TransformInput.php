<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $transformer): Response
    {
        $transformedInput = [];

        foreach($request->request->all() as $input => $value){

            $transformedInput[$transformer::originalAttribute($input)] = $value;

        }

        $request->replace($transformedInput);

        $response =  $next($request);

        if(isset($response->exception) && $response->exception instanceof ValidationException){

            $data = $response->getData();

            $transformedErrors = [];

            foreach($data->error as $field => $error){

             $transformedField = $transformer::transformedAttribute($field);

             $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);


            }


            $data->error = $transformedErrors;

            $response->setData($data);

        }

        return $response;
    }
}
