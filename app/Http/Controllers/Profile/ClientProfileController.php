<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Interface\Profile\ProfileInterface;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientProfileController extends Controller implements ProfileInterface
{

    public function getProfile()
    {
        $user_id = Auth::user()->id;
        $client = Client::where('user_id', $user_id)->with('user')->first();
        if($client == null)
            return response()->json(['message' =>  'لا يوجد زبون !']);

        return response()->json(['message' =>  'معلوماتك الشخصية','data' => $client]);
    }

    public function editProfile(Request $request)
    {

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg,bmp',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 422);
        }

        $upload = new HelperController();
        $image =  $upload->upload_image_localy($request, 'image', 'UserPhoto/ClientProfile/');

        if ($request->password != null)    $user['password'] = bcrypt($request['password']);
        if ($request->phone_number != null) $user['phone_number'] = $request->phone_number;
        if ($request->image != null)       $user['image'] = $image;

        $user->update();

        return response()->json(['message' =>  'تمت عملية التعديل بنجاح' ,'data'=>$user]);
    }
}
