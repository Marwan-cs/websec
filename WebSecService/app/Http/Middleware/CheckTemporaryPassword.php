<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\PasswordReset;

class CheckTemporaryPassword
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $temporaryPassword = PasswordReset::where('email', Auth::user()->email)->first();
            if ($temporaryPassword && !in_array($request->route()->getName(), ['change.password', 'change.password.submit', 'do_logout'])) {
                return redirect()->route('change.password');
            }
        }

        return $next($request);
    }
}