<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Interface\Profile\ProfileInterface;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AdminProfileController extends Controller implements ProfileInterface
{
    public function getProfile()
    {
        $user_id = Auth::user()->id;
        $admin = Admin::where('user_id', $user_id)->with('user','role')->first();
        if($admin == null)
            return response()->json(['message' =>  'لا يوجد مدير !']);
        return response()->json(['message' =>  'المعلومات الشخصية','data' => $admin]);
    }

  
    public function editProfile(Request $request)
    {

        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg',
            'password' => 'min:6'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $upload = new HelperController();
        $image =  $upload->upload_image_localy($request, 'image', 'UserPhoto/AdminProfile/');

        if ($request->password != null)    $user['password'] = bcrypt($request['password']);
        if ($request->phone_number != null) $user['phone_number'] = $request->phone_number;
        if ($request->image != null)       $user['image'] = $image;

        $user->update();
        return response()->json(['message' =>  'تمت عملية التعديل بنجاح']);
    }


}
