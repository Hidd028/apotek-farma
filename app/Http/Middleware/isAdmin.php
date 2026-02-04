<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->jabatan === 'Admin') {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Anda Tidak Punya Akses');
    }
}

