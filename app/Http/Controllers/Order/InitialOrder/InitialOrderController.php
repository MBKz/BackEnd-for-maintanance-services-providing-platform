<?php

namespace App\Http\Controllers\Order\InitialOrder;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Models\Client;
use App\Models\InitialOrder;
use App\Models\ServiceProvider;
use App\Models\User;
use App\Notifications\SendPushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InitialOrderController extends Controller
{
    public function get_all_for_client()
    {
        $user_id = auth()->user()->id;
        $client = Client::where('user_id', $user_id)->first();
        $initialOrders  = InitialOrder::with('job', 'state', 'city', 'client', 'proposal')
            ->where('client_id', $client->id)
            ->where('state_id','!=',4)
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

        $initialOrders = InitialOrder::with('job', 'state', 'city', 'client')
            ->select('*')
            ->where('city_id', '=', $service_provider->city_id)
            ->where('job_id', '=', $service_provider->job_id)
            ->where(function ($query) {
                $query->where('initial_orders.state_id', '=', 1)
                    ->orWhere('initial_orders.state_id', '=', 2);
            })
            ->whereNotIn('id', (function ($query) use ($service_provider) {

                $query->from('proposals')
                    ->select('initial_order_id')
                    ->where('service_provider_id', '=', $service_provider->id);
            }))
            ->get();


        return response()->json([
            "message" => "جميع الخدمات المطلوبة",
            "data" => $initialOrders
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
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


        // image process
        $upload = new HelperController();

        $images = $request->image;
        if ($request->image != null) {

            $echImages[count($images)] = null;
            for ($i = 0; $i < count($images); $i++) {

                $image =  $upload->upload_array_of_images_localy($request, 'image', 'initialOrder/', $i);

                $initialOrder->order_gallery()->create([
                    'title' => $request->title,
                    'image' => $image,
                    'initial_order_id' => $initialOrder->id
                ]);
            }
        }

        // notify all related service provider
        //TODO: test
        $providers = ServiceProvider::select('id','user_id','device_token')
            ->where('city_id' ,$initialOrder->city_id)
            ->where('job_id' ,$initialOrder->job_id)
            ->where('account_status_id' ,1)
            ->where('device_token' ,'!=', null)
            ->get();

        $message = $initialOrder->id.'هناك من يحتاج إلى خدمة صيانة ,هل أنت جاهز !';
        $title = 'جاهز للعمل ؟' ;
        foreach ($providers as $provider){
            $provider->notify(new SendPushNotification($title,$message,'order request'));
            $user= User::find($provider->user_id);
            $user->notifications()->create([
                'message' => $title,
                'body' => $message,
                'checked' => false,
                'date' => Carbon::now()->addHour(3)
            ]);
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
