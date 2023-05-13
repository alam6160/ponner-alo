<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

use App\Models\User;

class UserBeforeLogin
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
        if(Auth::check())
        {
            $user_data = User::where(['id' => Auth::id(), 'status' => '2', 'user_type' => '9'])->first();

            if(!empty($user_data))
            {
                return redirect()->intended('/');
            }else{
                Auth::logout();
                return redirect('sign-out');
            }
        }else{
            return $next($request);
        }
    }
}
