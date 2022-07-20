<?php

namespace App\Http\Middleware;

use App\Models\Client as ModelsClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Client
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
        $client = ModelsClient::where('user_id', $user_id)->first();
        if ($client == null) {
            return response(['errors'=>'عذرا لا تملك صلاحية'], 400);
     }

        return $next($request);
    }
}
