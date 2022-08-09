<?php

namespace App\Http\Controllers\Actors;

use App\Http\Controllers\Controller;
use App\Http\Interface\Actors\AdminInterface;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller implements AdminInterface
{

    public function getAdmins()
    {
        $admin  = Admin::with('user','role')->where('role_id' ,2)->orderBy('id', 'DESC')->get();
        return response()->json([
            "message" => "قائمة الموظفين",
            "data" => $admin
        ]);
    }

    public function CreateAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:users',
            'birthday' => 'required',
            'gender' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

       $user= User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' =>'$2y$10$D6qxVnoAHMJ./aWONwsQvug8w6dwfmjaaTJdTyGOQkBWP2yR9Jw3W',
            'birthday' => $request->birthday,
            'gender' => $request->gender,
        ]);

          $user->admin()->create(['role_id' => 2]);
        return response()->json([['message' =>  'تمت الإضافة بنجاح' ,'data' => $user->load('admin')]]);
    }

    public function destroy($id)
    {
        $admin = Admin::where('id', $id)->first();
        if ($admin == null) {
            return response()->json([
                "error" => "عذرا غير موجود"
            ], 404);
        }
        $user = User::where('id', $admin->user_id)->first();
        $admin->delete();
        $user->delete();

        return response()->json([
            "message" => "تمت عملية الحذف بنجاح",
            "data" => $admin
        ]);
    }
}
