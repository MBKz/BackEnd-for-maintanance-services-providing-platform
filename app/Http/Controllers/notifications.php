<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notifications extends Controller
{
    public function index(){
        $notifications = Notification::query()->orderBy('id', 'DESC')->where('user_id',Auth::user()->id)->get();
        return response(['message'=>'إشعاراتك' ,'data'=>$notifications],200);
    }

    public function destroy($id=null){
        if($id != null) {
            $notifications = Notification::query()->where('user_id', Auth::user()->id)
                ->where('id', $id)->first();
            $notifications->delete();

        }else{
            $notifications = Notification::query()->where('user_id', Auth::user()->id)->get();
            foreach ($notifications as $one){
                $one->delete();
            }
        }
        return response(['message' => 'تم الحذف'], 200);
    }
}
