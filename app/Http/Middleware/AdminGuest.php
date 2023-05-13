<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user_type = auth()->user()->user_type;
            $email_verified_at = auth()->user()->email_verified_at;
            if (!empty($email_verified_at) && ($user_type == 1 || $user_type == '2')) {
                return redirect()->route('admin.dashboard');
            }else{
                return $next($request);
            }
        }
        return $next($request);
    }
}
