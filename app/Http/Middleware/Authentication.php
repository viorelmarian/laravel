<?php

namespace App\Http\Middleware;

use Closure; 

class Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('logged')) {
            if ($request->ajax()) {
                throw new \Exception("Access denied", 403);
            }
            
            header('Location: /login');
            exit();
        }
        return $next($request);
    }
}
