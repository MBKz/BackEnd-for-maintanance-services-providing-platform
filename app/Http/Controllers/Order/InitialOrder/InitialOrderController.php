<?php

namespace App\Http\Controllers\Order\InitialOrder;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InitialOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InitialOrderController extends Controller
{
    public function get_all()
    {
        $user_id = auth()->user()->id;
        $client_id = Client::where('user_id',$user_id)->first();
        $initialOrders  = InitialOrder::with('job','state','city','client')
        ->where('client_id',$client_id->id)
        ->get(); 

        if ($initialOrders == null) {
            return response()->json([
                "message" => "Not Found Initial Orders"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Initial Orders List",
            "data" => $initialOrders
        ]);
    }
    public function store(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'description' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'job_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user_id = auth()->user()->id;
        $client_id = Client::where('user_id',$user_id)->first();

        $initialOrder = InitialOrder::create([
            'description' => $request->description,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'job_id' => $request->job_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'client_id' => $client_id->id,
        ]);
        return response()->json([
            "success" => true,
            "message" => "Initial Order created successfully.",
            "data" => $initialOrder
        ]);
    }
    public function update(Request $request, $id)
    {

        $initialOrder = InitialOrder::find($id);

        if ($initialOrder == null) {
            return response()->json([
                "message" => "Not Found Initial Order"
            ], 422);
        }

        if ($request->description != null)$initialOrder['description'] = $request->description;
        if ($request->location != null)   $initialOrder['location'] = $request->location;
        if ($request->latitude != null)   $initialOrder['latitude'] = $request->latitude;
        if ($request->longitude != null)  $initialOrder['longitude'] = $request->longitude;
        if ($request->job_id != null)     $initialOrder['job_id'] = $request->job_id;
        if ($request->state_id != null)   $initialOrder['state_id'] = $request->state_id;
        if ($request->city_id != null)    $initialOrder['city_id'] = $request->city_id;

        $initialOrder->update();

        return response()->json([
            "success" => true,
            "message" => "Initial Order updated successfully.",
            "data" => $initialOrder
        ]);
    }
}
