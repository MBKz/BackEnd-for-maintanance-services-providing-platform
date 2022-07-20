<?php

namespace App\Http\Controllers\Order\Proposal;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{

    public function get_all()
    {
        $user_id = auth()->user()->id;
        $client_id = ServiceProvider::where('user_id',$user_id)->first();
        $initialOrders  = Proposal::with('job','state','city','client')
        ->where('client_id',$client_id->id)
        ->get(); 

        return response()->json([
            "success" => true,
            "message" => "جميع الطلبات الخاصة بك",
            "data" => $initialOrders
        ]);
    }


    public function store(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'estimation_time' => 'required',
            'estimation_cost' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user_id = auth()->user()->id;
        $service_provider = ServiceProvider::where('user_id',$user_id)->first();

        $proposal = Proposal::create([
            'estimation_time' => $request->estimation_time,
            'estimation_cost' => $request->estimation_cost,
            'date' => $request->date,
            'service_provider_id' => $service_provider 
        ]);
        return response()->json([
            "success" => true,
            "message" => "تم الطلب بنجاح",
            "data" => $proposal
        ]);
    }

    
}
