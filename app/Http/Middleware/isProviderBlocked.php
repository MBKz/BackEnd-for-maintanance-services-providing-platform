<?php

namespace App\Http\Middleware;

use App\Models\ServiceProvider as ModelsServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isProviderBlocked
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
        $provider = ModelsServiceProvider::where('user_id', Auth::user()->id)->first();
        if( $provider->account_status_id  == 3 )    return response(['error'=>'لا يمكنك الوصول'], 403);
        return $next($request);
    }
}
