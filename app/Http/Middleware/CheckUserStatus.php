<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user login tapi statusnya nonaktif
        if (Auth::check() && Auth::user()->status !== 'aktif') {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'username' => 'Akun Anda telah dinonaktifkan oleh admin.'
            ]);
        }

        return $next($request);
    }
}
