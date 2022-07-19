<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        $user = User::firstWhere('email', $request->email);
        if($user == null){
        $random = rand(100000, 999999);
        $arr = [
            'title'    => 'أهلا بكم في عائلة خليها علينا',
            'body'     => 'الرجاء إدخال رمز التحقق في المكان المخصص له في التطبيق',
            'code'     =>  $random,
            'lastLine' => 'وشكرا'
        ];

        Notification::route('mail', $request->email)->notify(new MailNotification($arr));


        return response()->json(['message' => 'تم إرسال رمز التفعيل إلى حسابك' ,
        'email' =>  $request->email ,
        'code' => $random
        ]);
    }
    return response()->json(['error' => 'عذرا حسابك مفعل مسبقا'],400);
    }
}
