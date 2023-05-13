<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidateVendorRoute
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

        if (Auth::user()->vendor_type == '2') {
            $subscriptionLog = \App\Models\SubscriptionLog::whereAgentId( Auth::id() )->where('expaire_date', '>=', date('Y-m-d'))->orderBy('id', 'desc')->first();
            if (blank($subscriptionLog)) {
                return redirect()->route('agent.dashboard');
            } else {
                return $next($request);
            }
        } elseif (Auth::user()->vendor_type == '1') {
            return $next($request);
        }
        
    }
}
