<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\ServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderANDClient
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
        $client = Client::where('user_id', $user_id)->first();
        $service = ServiceProvider::where('user_id', $user_id)->first();

        if ($service != null) {
            if($service->account_status_id ==4)
            {
                return response()->json(['errors'=>'You Are Waiting For Acceptance'], 422);
             }

             if($service->account_status_id ==3)
             {
                return response()->json(['errors'=>'You Are Blocked'], 422);
             }
     }
     
        if ($client == null && $service ==null) {

            return response()->json(['errors'=>'You do not have access here'], 422);
     }

        return $next($request);
    }
}
