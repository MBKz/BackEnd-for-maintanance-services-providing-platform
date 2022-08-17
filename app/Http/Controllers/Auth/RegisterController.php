<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Interface\Auth\RegisterInterface;
use App\Models\Identity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller implements RegisterInterface
{

    public function registerServiceProvider(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'birthday' => 'required',
            'gender' => 'required',
            'city_id' => 'required',
            'job_id' => 'required',
            'number' => 'required|min:11|max:11|unique:identities',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $identity = Identity::create([
            'number' => $request->number,
            'image' => HelperController::upload_image($request->file('image'), 'Khalea-alena_app/Identities/')
        ]);

        $request['password'] = bcrypt($request['password']);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' =>  $request['password'],
            'birthday' => $request->birthday,
            'gender' => $request->gender,
        ]);

        $user->serviceProvider()->create([
            'city_id' => $request->city_id,
            'job_id' => $request->job_id,
            'account_status_id' => 4,
            'identity_id' => $identity->id
        ]);

        return response([
            'message' =>  'تمت عملية تسجيل الحساب بنجاح .'
        ]);
    }

    public function registerClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'birthday' => 'required',
            'gender' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $request['password'] = bcrypt($request['password']);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' =>  $request['password'],
            'birthday' => $request->birthday,
            'gender' => $request->gender,
        ]);

        $user->client()->create();


        return response([
            'message' =>  'تمت عملية تسجيل الحساب بنجاح .'
        ]);
    }

}
