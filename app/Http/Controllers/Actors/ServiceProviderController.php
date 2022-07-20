<?php

namespace App\Http\Controllers\Actors;

use App\Http\Controllers\Controller;
use App\Http\Interface\Actors\ServiceProviderInterface;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ServiceProviderController extends Controller implements ServiceProviderInterface
{

    public function getAllServiceProvider()
    {
        $serviceProvider  = ServiceProvider::with('user','job','account_status','city')->get();

        return response()->json([
            "message" => "قائمة مزودي الخدمات",
            "data" => $serviceProvider
        ]);
    }

    public function AcceptProvider(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'accept' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $serviceProvider = ServiceProvider::where('id',$id)->first();
        if($serviceProvider ==null) return response()->json(['error' =>  'مزود الخدمة غير موجود'],404);

        $user = $serviceProvider->user(); //User::where('id', $serviceProvider->user_id)->first();

        if ($request->accept == false) {
            $serviceProvider->delete();
            $user->delete();
            return response()->json([
                "message" => "تم رفض الطلب",
                "data" => [$user]
            ], 200);
        }

        $serviceProvider['account_status_id'] = 1;
        $serviceProvider->update();
        return response()->json([[
            'message' =>  'تمت إضافة مزود خدمة جديد',
            'data' =>$serviceProvider
        ]]);
    }

    public function getProviderRequests()
    {
        $serviceProvider  = ServiceProvider::with('user','job','account_status','city')->where('account_status_id',4)->get();
        return response()->json([
            "message" => "طلبات انضمام مزودي الخدمات",
            "data" => $serviceProvider
        ]);
    }



    public function block(Request $request,$id)
    {
        // TODO: Implement block() method.
    }

    public function unblock(Request $request,$id)
    {
        // TODO: Implement unblock() method.
    }


    public function switchStatus()
    {
        $serviceProvider = ServiceProvider::where('user_id',Auth::user()->id)->first();
        if($serviceProvider ==null)
        {
            return response()->json(['error' =>  'Not Found Service Provider'],404);
        }

        if($serviceProvider['account_status_id'] == 1) $serviceProvider['account_status_id'] = 2;
        elseif ($serviceProvider['account_status_id'] == 2) $serviceProvider['account_status_id'] = 1;

        $serviceProvider->update();
        return response()->json(['message' =>  'حالة موزد الخدمة الحالية ' .$serviceProvider->account_status->title]);
    }
}
