<?php

namespace App\Http\Controllers\Order\Proposal;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ServiceProvider;
use \App\Models\Client;
use App\Models\User;
use App\Notifications\SendPushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{

    public function get_all_for_provider()
    {
        $user_id = auth()->user()->id;
        $service_provider = ServiceProvider::where('user_id',$user_id)->first();
        $proposal  = Proposal::with('initial_order.client.user','initial_order.city','initial_order.job','initial_order.order_gallery','state')
        ->where('service_provider_id',$service_provider->id)
        ->where('state_id','!=',4)
        ->orderBy('id', 'DESC')->get();

        return response()->json([
            "message" => "جميع الطلبات الخاصة بك",
            "data" => $proposal
        ]);
    }

    public function get_all_for_client($id)
    {

        $proposals = Proposal::where('initial_order_id' ,$id)->with('state')
            ->orderBy('id', 'DESC')->get();

        return response()->json([
            "message" => "جميع الطلبات الخاصة بك",
            "data" => $proposals
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'estimation_time' => 'required',
            'estimation_cost' => 'required',
            'date' => 'required',
            'initial_order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 400);
        }

        $user_id = auth()->user()->id;
        $service_provider = ServiceProvider::where('user_id',$user_id)->first();

        $proposal = Proposal::create([
            'estimation_time' => $request->estimation_time,
            'estimation_cost' => $request->estimation_cost,
            'date' => $request->date,
            'note' => $request->note,
            'initial_order_id' => $request->initial_order_id,
            'service_provider_id' => $service_provider->id,
            'state_id' => 1,
        ]);

        $proposal->initial_order()->update([
            'state_id' => 2,
        ]);

        // TODO:notify
        $message = 'لديك عرض صيانة جديد بمعرف #'.$proposal->id.' من أجل الطلب ذو المعرف #'.$proposal->initial_order_id.' سيتم حذف العرض تلقائيا بعد 24 ساعة مالم يتم قبوله' ;
        $client = Client::where('id',$proposal->initial_order->client_id)->first();
        $client->notify(new SendPushNotification(' عرض صيانة',$message,'proposal'));
        $user= User::find($client->user_id);
        $user->notifications()->create([
            'message' => ' عرض صيانة',
            'body' => $message,
            'checked' => false,
            'date' => Carbon::now()->addHour(3)
        ]);

        return response()->json([
            "message" => "تم ارسال عرض الصيانة بنجاح",
            "data" => $proposal
        ]);
    }

    public function destroy($id)
    {

        $proposal = Proposal::where('id', $id)->first();

        if ($proposal == null) {
            return response()->json([
                "error" => "هذا الطلب غير موجود"
            ], 404);
        }

        $proposal->delete();

        return response()->json([
            "message" => "تم حذف هذا العرض بنجاح",
            "data" => $proposal
        ]);
    }


}
