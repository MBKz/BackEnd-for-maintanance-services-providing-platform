<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\MailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ConfirmController extends Controller
{
    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $random = rand(100000, 999999);
        $arr = [
            'title'    => 'Hi',
            'body'     => 'Please fill out this code in until khalea-alena Confirm your account app',
            'button'   => 'Confirm your account',
            'code'     =>  "this is your code : " . $random,
            'lastLine' => 'Thanks'
        ];


        Notification::route('mail', $request->email)->notify(new MailNotification($arr));


        return response()->json([['message' => 'Your application has been successfully' ,
        'email' =>  $request->email ,
        'code' => $random
        ]]);
       
    }
}
