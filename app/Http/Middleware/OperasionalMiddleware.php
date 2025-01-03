<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class OperasionalMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role === 'superadmin' || 
            Auth::user()->role === 'admin' || 
            Auth::user()->role === 'operasional') {
            return $next($request);
        }

        abort(403, 'Tidak Memiliki Akses!');
    }
}
