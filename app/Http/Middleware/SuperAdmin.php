<?php

namespace App\Http\Middleware;

use App\Models\Admin as ModelsAdmin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
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
        $admin = ModelsAdmin::where('user_id', $user_id)->first();
        if($admin != null && $admin->role_id == 1){
            return $next($request);
        }
        return response(['errors'=>'عذرا صلاحيات غير متاحة'], 403);
    }
}
