<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
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

        return response()->json(['message' =>  'Your Profile','data' => $admin]);   
    }

    public function editProfile(Request $request)
    {

        $user = Auth::user();


        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg',
        ]);


        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . $image->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'UserPhoto/AdminProfile',
                $image,
                $filename
            );
            $image = $request->image = url('/') . '/storage/' . 'UserPhoto' . '/' . 'AdminProfile' . '/' . $filename;
        }

        if ($request->password != null)    $user['password'] = bcrypt($user['password']);
        if ($request->phone_number != null) $user['phone_number'] = $request->phone_number;
        if ($request->image != null)       $user['image'] = $image;

        $user->update();

        return response()->json(['message' =>  'You Update Your Profile']);
    }


}
