<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        return $next($request);
    }
}
