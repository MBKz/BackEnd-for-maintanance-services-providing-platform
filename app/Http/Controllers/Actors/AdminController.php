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
        $admin  = Admin::with('user','role')->get();

        if ($admin == null) {
            return response()->json([
                "message" => "Not Found Admin"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Admins List",
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
            'age' => 'required',
            'gender' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
       

       $user= User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' =>'$2y$10$D6qxVnoAHMJ./aWONwsQvug8w6dwfmjaaTJdTyGOQkBWP2yR9Jw3W',
            'age' => $request->age,
            'gender' => $request->gender,
        ]);

        
        Admin::create([
            'user_id' =>$user->latest('id')->first()->id,
            'role_id' => 2,
        ]);
       

        return response()->json([['message' =>  'Successfully added']]);
    }


    public function destroy($id)
    {
        $admin = Admin::where('id', $id)->first();

        if ($admin == null) {
            return response()->json([
                "message" => "Not Found Admin"
            ], 422);
        }
        $admin->delete();

        return response()->json([
            "success" => true,
            "message" => "Admin deleted successfully ",
            "data" => $admin
        ]);
    }



}
