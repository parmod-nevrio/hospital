<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            return redirect()->route('login')->with('error', 'You do not have permission to access the patient portal.');
        }

        return $next($request);
    }
}
