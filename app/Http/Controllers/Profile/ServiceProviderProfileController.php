<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Interface\Profile\ProfileInterface;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . $image->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'UserPhoto/ServiceProviderProfile',
                $image,
                $filename
            );
            $image = $request->image = url('/') . '/storage/' . 'UserPhoto' . '/' . 'ServiceProviderProfile' . '/' . $filename;
        }

        if ($request->password != null)    $user['password'] = bcrypt($request['password']);
        if ($request->phone_number != null) $user['phone_number'] = $request->phone_number;
        if ($request->image != null)       $user['image'] = $image;

        $user->update();

        return response()->json(['message' =>  'تم تعديل البروفايل']);
    }

}
