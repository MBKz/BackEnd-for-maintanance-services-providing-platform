<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Interface\Auth\LoginInterface;
use App\Models\Admin;
use App\Models\Client;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller implements LoginInterface
{

    public function loginAdmin(Request $request)
    {
        $input = Validator::make($request->all() ,[
            'email' => 'email|required',
            'password' => 'required',
        ]);
        if($input->fails())
            return response(['error'=>$input->errors()->all()],400);

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user_id = Auth::user()->id;
            $admin = Admin::where('user_id', $user_id)->first();
            $token =  Auth::user()->createToken('KhaleaAlena')->accessToken;
            if ($admin != null ) {
                return response([
                    'message' =>  'مرحبا بكم من جديد',
                    'token' => $token,
                ]);
            }
        }
            return response()->json(['error' => 'الحساب غير متاح'], 404);
    }


    public function loginServiceProvider(Request $request)
    {
        $input = Validator::make($request->all() ,[
            'email' => 'email|required',
            'password' => 'required',
            'device_token' => 'required'
        ]);
        if($input->fails())
            return response(['error'=>$input->errors()->all()],400);

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user_id = Auth::user()->id;
            $provider = ServiceProvider::where('user_id', $user_id)->first();
            if ($provider != null) {
                $token =  Auth::user()->createToken('KhaleaAlena')->accessToken;
                $provider->update([
                    'device_token' => $request->device_token
                ]);
                return response([
                    'message' =>  'مرحبا بكم من جديد',
                    'token' => $token,
                ]);
            }
        }
        return response()->json(['error' => 'عذرا الرجاء الاشتراك بالنظام أولا'], 400);

    }

    public function loginClient(Request $request)
    {
        $input = Validator::make($request->all() ,[
            'email' => 'email|required',
            'password' => 'required',
            'device_token' => 'required'
        ]);
        if($input->fails())
            return response(['error'=>$input->errors()->all()],400);

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user_id = Auth::user()->id;
            $client = Client::firstWhere('user_id', $user_id);
            if ($client != null) {
                $token =  Auth::user()->createToken('KhaleaAlena')->accessToken;
                $client->update([
                    'device_token' => $request->device_token
                ]);
                return response([
                    'message' =>  'مرحبا بكم من جديد',
                    'token' => $token,
                ]);
            }
        }

        return response()->json(['error' => 'عذرا الرجاء الاشتراك بالنظام أولا'], 400);
    }


}
