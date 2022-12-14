<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Interface\Profile\ProfileInterface;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;

class ServiceProviderProfileController extends Controller implements ProfileInterface
{
    public function getProfile()
    {
        $user_id = Auth::user()->id;
        $provider = ServiceProvider::where('user_id', $user_id)->with('user','job','account_status','city')->first();
        if($provider == null)
            return response()->json(['message' =>  'لا يوجد مزود خدمة !']);

        return response()->json(['message' =>  'معلوماتك الشخصية', 'data' => $provider]);
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

        if ($request->password != null)    $user['password'] = bcrypt($request['password']);
        if ($request->phone_number != null) $user['phone_number'] = $request->phone_number;
        if ($request->image != null)  {
            if($user['image'] != null)  HelperController::cloudinary_delete($user['image']);
            $user['image'] = HelperController::upload_image($request->file('image'), 'Khalea-alena_app/profiles');;
        }

        $user->update();

        return response()->json(['message' =>  'تم تعديل البروفايل']);
    }

}
