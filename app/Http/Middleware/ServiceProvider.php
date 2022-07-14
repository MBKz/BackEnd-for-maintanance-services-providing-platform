<?php

namespace App\Http\Middleware;

use App\Models\ServiceProvider as ModelsServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceProvider
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

        $user_id = Auth::user()->id;
        $provider = ModelsServiceProvider::where('user_id', $user_id)->first();
        if ($provider != null) {
            if($provider->account_status_id ==4)
            {
                return response()->json(['errors'=>'You Are Waiting For Acceptance'], 422);
             }

             if($provider->account_status_id ==3)
             {
                return response()->json(['errors'=>'You Are Blocked'], 422);
             }
     }
     else
     return response()->json(['errors'=>'You do not have access here'], 422);

     return $next($request);

}

}
