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

        $provider = ModelsServiceProvider::where('user_id', Auth::user()->id)->first();
        if ($provider == null)  return response(['errors'=>'عذرا ليست من صلاحياتك'], 403);
        if($provider->account_status_id ==4)   return response(['errors'=>'لم يتم قبول طلب انضمامك بعد'], 403);
        return $next($request);
    }
}
