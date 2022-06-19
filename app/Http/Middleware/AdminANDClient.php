<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminANDClient
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
        $admin = Admin::where('user_id', $user_id)->first();
        if ($client == null && $admin ==null) {

            return response()->json(['errors'=>'You do not have access here'], 422);
     }

        return $next($request);
    }
}
