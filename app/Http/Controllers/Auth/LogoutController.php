<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Interface\Auth\LogoutInterface;
use Illuminate\Http\Request;
use PHPUnit\Exception;


class LogoutController extends Controller implements LogoutInterface
{
    public function logout(Request $request)
     {
         $request->user()->token()->revoke();
         return response()->json(['message' => 'تمت عملية تسجيل الخروج بنجاح']);
     }
}
