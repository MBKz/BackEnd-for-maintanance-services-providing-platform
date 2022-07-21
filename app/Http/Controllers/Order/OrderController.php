<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InitialOrder;
use App\Models\Order;
use App\Models\Proposal;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function order_confirm($id)
    {
        $order = Order::create([
            'proposal_id' => $id,
            'state_id' => 1,
        ]);

        $order->proposal()->update([
            'state_id' => 4,
        ]);

        $order->proposal->initial_order()->update([
            'state_id' => 4,
        ]);

        $proposal = Proposal::where('id', $id)->first();

        $proposals = Proposal::where('initial_order_id', $proposal->initial_order_id)->get();

        foreach ($proposals as $one) {
            if ($one->id != $id)
                $one->delete();
        }

        return response()->json([
            "message" => "تم تأكيد الطلب",
            "data" => $order
        ]);
    }

    public function order_start($id)
    {
        $order = Order::find($id);

        $order['start_date'] = now();
        $order['state_id'] = 3;

        $order->update();

        $order->proposal()->update([
            'state_id' => 3,
        ]);

        return response()->json([
            "message" => "تم بدء العمل",
            "data" => $order
        ]);
    }

    public function order_end(Request $request, $id)
    {
        $order = Order::find($id);

        $validator = Validator::make($request->all(), ['cost' => 'required']);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $order['end_date'] = now();
        $order['state_id'] = 4;
        $order['cost'] = $request->cost;

        $order->update();

        $order->proposal()->update([
            'state_id' => 4,
        ]);



        return response()->json([
            "message" => "تم انهاء العمل",
            "data" => $order
        ]);
    }

    public function order_current_for_client()
    {

        $osrderCurrent = Order::whereHas('proposal.initial_order', function ($q) {
            $user_id = auth()->user()->id;
            $client = Client::where('user_id', $user_id)->first();
            $q->where('client_id', $client->id);
        })->where('state_id', 1)->orWhere('state_id', '3')->get();

        return response()->json([
            "message" => "طلباتي الحالية",
            "data" => $osrderCurrent
        ]);
    }

    public function order_current_for_provider()
    {

        $osrderCurrent = Order::whereHas('proposal', function ($q) {
            $user_id = auth()->user()->id;
            $service_provider = ServiceProvider::where('user_id', $user_id)->first();
            $q->where('service_provider_id', $service_provider->id);
        })->where('state_id', 1)->orWhere('state_id', 3)->get();

        return response()->json([
            "message" => "طلباتي الحالية",
            "data" => $osrderCurrent
        ]);
    }

    public function order_history_for_client()
    {

        $osrderCurrent = Order::with('proposal','proposal.initial_order')->whereHas('proposal.initial_order', function ($q) {
            $user_id = auth()->user()->id;
            $client = Client::where('user_id', $user_id)->first();
            $q->where('client_id', $client->id);
        })
        ->where('state_id', 4)->get();

        return response()->json([
            "message" => "طلباتي المنجزة",
            "data" => $osrderCurrent
        ]);
    }

    public function order_history_for_provider()
    {

        $osrderCurrent = Order::whereHas('proposal', function ($q) {
            $user_id = auth()->user()->id;
            $service_provider = ServiceProvider::where('user_id', $user_id)->first();
            $q->where('service_provider_id', $service_provider->id);
        })->where('state_id', 4)->get();

        return response()->json([
            "message" => "طلباتي المنجزة",
            "data" => $osrderCurrent
        ]);
    }

    public function all_orders(){
        $orders = Order::with('review' ,'state')->get();
        return response(['message' => 'قائمة الطلبات' ,'data'=>$orders],200);
    }

    public function all_initials(){
        $orders = InitialOrder::with('job' ,'state' ,'city')->get();
        return response(['message' => 'قائمة الخدمات المطلوبة' ,'data'=>$orders],200);
    }

    public function all_proposals(){
        $orders = Proposal::with('state')->get();
        return response(['message' => 'قائمة عروض الصيانة المقدمة' ,'data'=>$orders],200);
    }

}
