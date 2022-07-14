<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Interface\Auth\LogoutInterface;
use Illuminate\Http\Request;



class LogoutController extends Controller implements LogoutInterface
{
    public function logout(Request $request)
     {

        $user = $request->user()->token();
        $user->revoke();

        return response()->json([
            'success' => true,
            'message' => 'Logout successfully'
        ]);
    }
}
