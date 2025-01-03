<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class FinanceMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role === 'superadmin' || 
            Auth::user()->role === 'admin' || 
            Auth::user()->role === 'finance') {
            return $next($request);
        }

        abort(403, 'Tidak Memiliki Akses!');
    }
}
