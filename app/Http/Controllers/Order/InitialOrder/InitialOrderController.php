<?php

namespace App\Http\Controllers\Order\InitialOrder;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InitialOrder;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                "error" => "Not Found Initial Orders"
            ], 422);
        }

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
            'description' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'job_id' => 'required',
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
            'state_id' => 1,
            'city_id' => $request->city_id,
            'client_id' => $client_id->id,
        ]);
        return response()->json([
            "success" => true,
            "message" => "تم الطلب بنجاح",
            "data" => $initialOrder
        ]);
    }
    public function update(Request $request, $id)
    {

        $initialOrder = InitialOrder::find($id);

        if ($initialOrder == null) {
            return response()->json([
                "error" => "هذا الطلب غير موجود"
            ], 422);
        }

        if ($request->description != null)$initialOrder['description'] = $request->description;
        if ($request->location != null)   $initialOrder['location'] = $request->location;
        if ($request->latitude != null)   $initialOrder['latitude'] = $request->latitude;
        if ($request->longitude != null)  $initialOrder['longitude'] = $request->longitude;
        if ($request->job_id != null)     $initialOrder['job_id'] = $request->job_id;
        if ($request->city_id != null)    $initialOrder['city_id'] = $request->city_id;

        $initialOrder->update();

        return response()->json([
            "success" => true,
            "message" => "تم تعديل هذا الطلب بنجاح",
            "data" => $initialOrder
        ]);
    }

    public function destroy($id)
    {

        $user_id = Auth::id();
        $client = Client::where('user_id', $user_id)
            ->first();

        $client_id = $client->id;
        $initalOrder = InitialOrder::where('id', $id)
            ->where('client_id', $client_id)
            ->first();
            

        if ($initalOrder == null) {
            return response()->json([
                "error" => "هذا الطلب غير موجود"
            ], 422);
        }
        $initalOrder->delete();

        return response()->json([
            "success" => true,
            "message" => "تم حذف هذا الطلب بنجاح",
            "data" => $initalOrder
        ]);
    }
}
