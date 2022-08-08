<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Interface\Auth\LogoutInterface;
use App\Models\Client;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use PHPUnit\Exception;


class LogoutController extends Controller implements LogoutInterface
{
    public function logout(Request $request)
     {
         $user_id = auth()->user()->id;
         $client = Client::where('user_id', $user_id)->first();
         $service = ServiceProvider::where('user_id', $user_id)->first();

         if($client != null) $client->update(['device_token' => '']);
         if($service != null) $service->update(['device_token' => '']);

         $request->user()->token()->revoke();
         return response()->json(['message' => 'تمت عملية تسجيل الخروج بنجاح']);
     }
}
