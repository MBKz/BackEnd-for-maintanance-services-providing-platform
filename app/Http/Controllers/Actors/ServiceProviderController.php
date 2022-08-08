<?php

namespace App\Http\Controllers\Actors;

use App\Http\Controllers\Controller;
use App\Http\Interface\Actors\ServiceProviderInterface;
use App\Models\ServiceProvider;
use App\Models\User;
use App\Notifications\MailNotification;
use App\Notifications\SendPushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;


class ServiceProviderController extends Controller implements ServiceProviderInterface
{

    public function getAllServiceProvider()
    {
        $serviceProvider  = ServiceProvider::with('user','identity','job','account_status','city')
            ->where('account_status_id' ,'!=' ,4)
            ->get();

        return response()->json([
            "message" => "قائمة مزودي الخدمات",
            "data" => $serviceProvider
        ]);
    }

    // TODO: test mail
    public function AcceptProvider(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'accept' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 422);
        }

        $serviceProvider = ServiceProvider::where('id',$id)->first();
        if($serviceProvider ==null) return response()->json(['error' =>  'مزود الخدمة غير موجود'],404);

        $user = $serviceProvider->user();


        if ($request->accept == false) {
            $serviceProvider->delete();
            $user->delete();
            // inform email
            $arr = [
                'title'    => 'أهلا بكم في عائلة خليها علينا',
                'body'     => 'تم رفض طلب انضمامك إلى المنصة ,قد يكون سبب ذلك عدم وضوح أوراق الثبوتية الرجاء التحقق منها و إعادة المحاولة مرة أخرى',
                'code' => 'لا تتأخر ..',
                'lastLine' => 'بانتظار انضمامكم بعد تصحيح اوراق الثبوتية'
            ];
            Notification::route('mail', $user->email)->notify(new MailNotification($arr));

            return response()->json([
                "message" => "تم رفض الطلب",
                "data" => [$user]
            ], 200);
        }

        $serviceProvider['account_status_id'] = 1;
        $serviceProvider->update();
        // inform email
        $arr = [
            'title'    => 'أهلا بكم في عائلة خليها علينا',
            'body'     => 'تم قبول طلب انضمامك إلى المنصة ,يمكنك الآن تقديم عروض صيانة و مزاولة العمل',
            'code'     => 'ماذا تنتظر ! هيا نعمل ',
            'lastLine' => 'نتمنى لكم تجربة مريحة و مربحة'
        ];
        Notification::route('mail', $user->email)->notify(new MailNotification($arr));

        return response()->json([[
            'message' =>  'تمت إضافة مزود خدمة جديد',
            'data' =>$serviceProvider
        ]]);
    }

    public function getProviderRequests()
    {
        $serviceProvider  = ServiceProvider::with('user','identity','job','account_status','city')->where('account_status_id',4)->get();
        return response()->json([
            "message" => "طلبات انضمام مزودي الخدمات",
            "data" => $serviceProvider
        ]);
    }

    // TODO: notify test
    public function block(Request $request,$id)
    {
        $provider = ServiceProvider::firstWhere('id',$id);
        if($provider == null)    return response()->json(['error' =>  'عذرا مزود الخدمة غير موجود'],404);

        $provider['account_status_id'] = 3;
        $provider->update();

        //TODO:
        $message = 'لقد تم حظر حسابك من قبل المدير لمخالفتك بعض السياسات لايمكنك استقبال طلبات خدمة او تقديم عروض جديدة';
        $provider->notify(new SendPushNotification('نشاط الحساب',$message,'sys'));
        $user= User::find($provider->user_id);
        $user->notifications()->create([
            'message' => 'نشاط الحساب',
            'body' => $message,
            'checked' => false,
            'date' => Carbon::now()->addHour(3)
        ]);

        $block =$provider->block()->create([
            'duration' => 7,
            'start_date' => now(),
            'end_date' => Carbon::now()->addDays(7)
        ]);
        return response(['message' => 'تم حظر مزود الخدمة' ,'data' =>$block],200);
    }

    // TODO: notify test
    public function unblock(Request $request,$id)
    {
        $provider = ServiceProvider::firstWhere('id',$id);
        if($provider == null)    return response()->json(['error' =>  'عذرا مزود الخدمة غير موجود'],404);

        $provider->update(['account_status_id' => 1]);

        $message = 'لقد تم فك الحظر عن حسابك يمكنك استئناف نشاطك';
        $provider->notify(new SendPushNotification('نشاط الحساب',$message,'sys'));
        $user= User::find($provider->user_id);
        $user->notifications()->create([
            'message' => 'نشاط الحساب',
            'body' => $message,
            'checked' => false,
            'date' => Carbon::now()->addHour(3)
        ]);

        $provider->block()->delete();
        return response(['message' => 'تم فك الحظر عن مزود الخدمة' ,'data' =>$provider],200);
    }

    public function editActivity(Request $request)
    {

        $provider = ServiceProvider::where('user_id' ,Auth::user()->id);

        if ($request->city_id != null)
            $provider->update([
                'city_id' => $request->city_id
            ]);
        if ($request->account_status_id != null &&
                ( $request->account_status_id == 1 || $request->account_status_id == 2 ))
            $provider->update([
                'account_status_id' => $request->account_status_id
            ]);

        return response()->json(['message' =>  'تمت عملية التعديل بنجاح']);
    }

    public function getActivity()
    {

        $provider = ServiceProvider::with('city' ,'account_status')->where('user_id' ,Auth::user()->id)->first();
        $res = (object) [
            'city' => $provider->city->name ,
            'account_status' => $provider->account_status->title
        ];

        return response()->json(['message' =>  'حالة الحساب' ,'data' => $res]);
    }

}
