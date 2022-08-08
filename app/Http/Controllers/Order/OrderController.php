<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InitialOrder;
use App\Models\Order;
use App\Models\Proposal;
use App\Models\ServiceProvider;
use App\Models\User;
use App\Notifications\SendPushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    // TODO:notify
    // client
    public function order_confirm($id)
    {
        $proposal = Proposal::where('id', $id)->with('order')->first();

        if($proposal == null )
            return response(['message'=>'لا يمكن تثبيت الطلب' ],404);

        $proposal->order()->create([
            'proposal_id' => $id,
            'state_id' => 1,
        ]);

        $proposal->update([
            'state_id' => 4,
        ]);

        $proposal->initial_order()->update([
            'state_id' => 4,
        ]);

        $proposals = Proposal::where('initial_order_id', $proposal->initial_order_id)->get();

        foreach ($proposals as $one) {
            if ($one->id != $id)
                $one->delete();
        }

        // TODO:notify
        $message = 'تم قبول عرض الصيانة ذو المعرف #'.$proposal->id.' الذي قدمته من أجل الخدمة ذو المعرف #'.$proposal->initial_order_id ;
        $provider = ServiceProvider::where('id',$proposal->service_provider_id)->first();
        $provider->notify(new SendPushNotification('أصبح لديك طلب حالي لإنجازه',$message,'order'));
        $user= User::find($provider->user_id);
        $user->notifications()->create([
            'message' => 'أصبح لديك طلب حالي لإنجازه',
            'body' => $message,
            'checked' => false,
            'date' => Carbon::now()->addHour(3)
        ]);


        return response()->json([
            "message" => "تم تأكيد الطلب",
        ]);
    }

    public function order_current_for_client()
    {

        $client = Client::where('user_id', auth()->user()->id)->first();

        $osrderCurrent = Order::with('review','proposal.service_provider.user','proposal.initial_order.city','proposal.initial_order.job')
        ->whereHas('proposal.initial_order', function ($q) use($client) {
            $q->where('client_id', $client->id);
        })->where('state_id', 1)->orWhere('state_id', 3)->get();

        return response()->json([
            "message" => "طلباتي الحالية",
            "data" => $osrderCurrent
        ]);
    }

    public function order_history_for_client()
    {
        $client = Client::where('user_id', auth()->user()->id)->first();

        $osrderCurrent = Order::with('review','proposal.service_provider.user','proposal.initial_order.city','proposal.initial_order.job')
            ->whereHas('proposal.initial_order', function ($q) use ($client){
                $q->where('client_id', $client->id);
            })->where('state_id', 4)->get();

        return response()->json([
            "message" => "طلباتي المنجزة",
            "data" => $osrderCurrent
        ]);
    }

    // provider
    public function order_current_for_provider()
    {

        $service_provider = ServiceProvider::where('user_id', auth()->user()->id)->first();

        $osrderCurrent = Order::with('review','proposal.initial_order.client.user','proposal.initial_order.city','proposal.initial_order.job')
        ->whereHas('proposal', function ($q) use($service_provider) {
            $q->where('service_provider_id', $service_provider->id);
        })->where('state_id', 1)->orWhere('state_id', 3)->get();

        return response()->json([
            "message" => "طلباتي الحالية",
            "data" => $osrderCurrent
        ]);
    }

    public function order_start($id)
    {
        $order = Order::find($id);

        $order->update([
            'start_date' => now()->addHour(3),
            'state_id' => 3
        ]);

        return response()->json([
            "message" => "تم بدء العمل",
            "data" => $order
        ]);
    }

    public function order_end(Request $request, $id)
    {

        $validator = Validator::make($request->all(), ['cost' => 'required']);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 422);
        }

        $order = Order::find($id);

        $order->update([
            'end_date' => now()->addHour(3),
            'state_id' => 4,
            'cost' => $request->cost
        ]);

        return response()->json([
            "message" => "تم انهاء العمل",
            "data" => $order
        ]);
    }

    public function order_history_for_provider()
    {
        $service_provider = ServiceProvider::where('user_id',  auth()->user()->id)->first();

        $osrderCurrent = Order::with('review','proposal.initial_order.client.user' ,'proposal.initial_order.city','proposal.initial_order.job')
        ->whereHas('proposal', function ($q) use($service_provider){
            $q->where('service_provider_id', $service_provider->id);
        })->where('state_id', 4)->get();

        return response()->json([
            "message" => "طلباتي المنجزة",
            "data" => $osrderCurrent
        ]);
    }

    // admin
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
