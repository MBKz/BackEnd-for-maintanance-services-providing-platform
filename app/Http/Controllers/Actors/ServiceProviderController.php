<?php

namespace App\Http\Controllers\Actors;

use App\Http\Controllers\Controller;
use App\Http\Interface\Actors\ServiceProviderInterface;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ServiceProviderController extends Controller implements ServiceProviderInterface
{ 

    public function getAllServiceProvider() 
    {
        $serviceProvider  = ServiceProvider::with('user','job','account_status','city')->get();

        if ($serviceProvider == null) {
            return response()->json([
                "message" => "Not Found Service Provider"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Service Providers List",
            "data" => $serviceProvider
        ]);
    }

    public function getProviderActivited() 
    {
        $serviceProvider  = ServiceProvider::with('user','job','account_status','city')->where('account_status_id',1)->get();

        if ($serviceProvider == null) {
            return response()->json([
                "message" => "Not Found Service Provider Activited"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Service Providers Activited List",
            "data" => $serviceProvider
        ]);
    }

        public function getProviderUnActive() 
    {
        $serviceProvider  = ServiceProvider::with('user','job','account_status','city')->where('account_status_id',2)->get();

        if ($serviceProvider == null) {
            return response()->json([
                "message" => "Not Found Service Provider (Not Activited)"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Service Providers Not Activited List",
            "data" => $serviceProvider
        ]);
    }

    public function getProviderBlocked() 
    {
        $serviceProvider  = ServiceProvider::with('user','job','account_status','city')->where('account_status_id',3)->get();

        if ($serviceProvider == null) {
            return response()->json([
                "message" => "Not Found Service Provider Blocked"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Service Providers Blocked List",
            "data" => $serviceProvider
        ]);
    }


    public function getProviderRequests() 
    {
        $serviceProvider  = ServiceProvider::with('user','job','account_status','city')->where('account_status_id',4)->get();

        if ($serviceProvider == null) {
            return response()->json([
                "message" => "Not Found Service Provider Requests"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Service Providers Requests List",
            "data" => $serviceProvider
        ]);
    }

    public function AcceptProvider(Request $request,$id){

        
        $validator = Validator::make($request->all(), [
            'account_status_id' => 'required'
        ]);


        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }



        $serviceProvider = ServiceProvider::where('user_id',$id)->first();

        if($serviceProvider ==null)
        {
            return response()->json([['error' =>  'Not Found Service Provider']]);
        }

        $serviceProvider['account_status_id'] = $request->account_status_id;

        $serviceProvider->update();


        return response()->json([[
            'message' =>  'The service provider has become active.',
            'data' =>$serviceProvider
            ]]);


    }

    public function swichActivitProvider(Request $request){

        
        $validator = Validator::make($request->all(), [
            'account_status_id' => 'required'
        ]);


        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }



        $serviceProvider = ServiceProvider::where('user_id',Auth::user()->id)->first();

        if($serviceProvider ==null)
        {
            return response()->json([['error' =>  'Not Found Service Provider']]);
        }

        $serviceProvider['account_status_id'] = $request->account_status_id;

        $serviceProvider->update();


        return response()->json([[
            'message' =>  'The service provider has become ' .$serviceProvider->account_status->title,
            'data' =>$serviceProvider
            ]]);


    }

}
