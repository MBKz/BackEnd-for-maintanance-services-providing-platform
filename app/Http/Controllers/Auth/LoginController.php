<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Interface\Auth\LoginInterface;
use App\Models\Admin;
use App\Models\Client;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller implements LoginInterface
{

    public function loginAdmin()
    {

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user_id = Auth::user()->id;
            $admin = Admin::where('user_id', $user_id)->first();
            $token =  Auth::user()->createToken('KhaleaAlena')->accessToken;
            if ($admin != null && $admin->role_id == 1) {
                return response([
                    'message' =>  'You have been successfully SuperAdmin login',
                    'token' => $token,
                ]);
            }
            else
            return response([
                'message' =>  'You have been successfully Admin login',
                'token' => $token,
            ]);
        }
            return response()->json(['error' => 'كلمة السر غير صحيحة أو الحساب غير صحيح'], 422);
    }


    public function loginServiceProvider()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user_id = Auth::user()->id;
            $provider = ServiceProvider::where('user_id', $user_id)->first();
            if ($provider != null) {
                $token =  Auth::user()->createToken('KhaleaAlena')->accessToken;
                return response([
                    'message' =>  'You have been successfully Service provider login',
                    'token' => $token,
                ]);
            }
        }
        
            return response()->json(['error' => 'كلمة السر غير صحيحة أو الحساب غير صحيح'], 422);
        
    }

    public function loginClient()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user_id = Auth::user()->id;
            $client = Client::where('user_id', $user_id)->first();
            if ($client != null) {
                $token =  Auth::user()->createToken('KhaleaAlena')->accessToken;
                return response([
                    'message' =>  'You have been successfully Client login',
                    'token' => $token,
                ]);
            }
        }

        return response()->json(['error' => 'كلمة السر غير صحيحة أو الحساب غير صحيح'], 422);
    }

    
}
