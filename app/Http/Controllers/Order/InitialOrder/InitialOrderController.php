<?php

namespace App\Http\Controllers\Order\InitialOrder;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InitialOrder;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InitialOrderController extends Controller
{
    public function get_all_for_client()
    {
        $user_id = auth()->user()->id;
        $client = Client::where('user_id', $user_id)->first();
        $initialOrders  = InitialOrder::with('job', 'state', 'city', 'client', 'proposal.service_provider')
            ->where('client_id', $client->id)
            ->get();

        return response()->json([
            "message" => "جميع الطلبات الخاصة بك",
            "data" => $initialOrders
        ]);
    }


    public function get_all_for_provider()
    {
        $user_id = auth()->user()->id;
        $service_provider = ServiceProvider::where('user_id', $user_id)
            ->where('account_status_id', 1)
            ->first();

        $init = InitialOrder::get();

        foreach ($init as $inital) {
            $initialOrders[]  = (InitialOrder::with('job', 'state', 'city', 'client')
                ->whereHas('proposal', function ($q)  use ($inital) {
                    $user_id = auth()->user()->id;
                    $service_provider = ServiceProvider::where('user_id', $user_id)
                        ->where('account_status_id', 1)
                        ->first();

                    $q->where('service_provider_id', $service_provider->id)
                        ->where('initial_order_id', '!=', $inital->id);
                })
                ->where('city_id', $service_provider->city_id)
                ->where('job_id', $service_provider->job_id)
            )->where('state_id', 1)
                ->orWhere('state_id', 2)
                ->get();
        }

        return response()->json([
            "message" => "جميع الخدمات المطلوبة",
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
            'image[]' => 'array|image|mimes:jpg,png,jpeg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user_id = auth()->user()->id;
        $client_id = Client::where('user_id', $user_id)->first();


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



        $images = $request->image;
        if ($request->image != null) {
            $echImages[count($images)] = null;


            for ($i = 0; $i < count($images); $i++) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = time() . $image[$i]->getClientOriginalName();
                    Storage::disk('public')->putFileAs(
                        'initialOrder',
                        $image[$i],
                        $filename
                    );
                    $image[$i] = $request->image = url('/') . '/storage/' . 'initialOrder' . '/' . $filename;
                    $echImages[$i] = $image[$i];
                } else {
                    $image[$i] = null;
                }
            }

            for ($i = 0; $i < count($image); $i++) {

                $initialOrder->order_gallery()->create([
                    'title' => $request->title,
                    'image' => $echImages[$i],
                    'initial_order_id' => $initialOrder->id
                ]);
            }
        }

        return response()->json([
            "message" => "تم الطلب بنجاح",
            "data" => $initialOrder
        ]);
    }

    public function destroy($id)
    {

        $initalOrder = InitialOrder::where('id', $id)->first();

        if ($initalOrder == null) {
            return response()->json([
                "error" => "هذا الطلب غير موجود"
            ], 404);
        }
        $initalOrder->delete();

        return response()->json([
            "success" => true,
            "message" => "تم حذف هذا الطلب بنجاح",
            "data" => $initalOrder
        ]);
    }
}
