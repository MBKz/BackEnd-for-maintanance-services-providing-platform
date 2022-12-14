<?php

namespace App\Http\Controllers\adminFunctions;

use App\Http\Controllers\Controller;
use App\Http\Interface\FAQ\FAQInterface;
use App\Models\City;
use App\Models\Client;
use App\Models\Faq;
use App\Models\Job;
use App\Models\Order;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class adminFunctionsController extends Controller implements FAQInterface
{

    public function backup(){

        $result = Artisan::call('backup:run');
//        dd(Artisan::output());
        if($result == 1)
            return response(['message'=>'فشلت عملية النسخ الاحتياطي , الرجاء المحاولة مرة أخرى','date'=>now() ]);
        return response(['message'=>'تمت عملية النسخ الاحتياطي بنجاح','date'=>now()]);
    }

    public function statistics()
    {
        $statistics = (object) [];
        $statistics->users = User::query()->count();
        $statistics->clients = Client::query()->count();
        $statistics->service_providers = ServiceProvider::query()->count();
        $statistics->coverd_cities = City::query()->count();
        $statistics->services = Job::query()->count();
        $statistics->waiting_to_lunch_orders = Order::query()->where('state_id','=',1)->count();
        $statistics->on_going_orders = Order::query()->where('state_id','=',3)->count();
        $statistics->achieved_orders = Order::query()->where('state_id','=',4)->count();
        $statistics->weekly_report = $this->statistic_chart();

        return response(['message'=>'إحصائيات النظام الحالية','statistics'=>$statistics],200);
    }

    public function statistic_chart(){
        $value = [];
        for($i=0 ; $i<7 ; $i++){
            $num = 0 ;
            $data = Order::query()->select('start_date as date',DB::raw( 'count(*) as num'))
                ->where(DB::raw( 'CAST(start_date as DATE)') ,'=' , now()->subDays($i)->toDateString())
                ->groupBy('start_date')
                ->get();
            if(count($data) == 1) $num = $data[0]->num ;
            else {
                foreach ($data as $one){
                    $num += $one->num;
                }
            }
            $value[$i] = $num;
        }
        return $value ;
    }

    // FAQ
    public function get_all(){

        $faqs  = Faq::with('tag')->where('answer' ,'!=' ,'null')->orderBy('id', 'DESC')->get();

        if ($faqs == null) {
            return response()->json([
                "message" => "عذرا لا يوجد"
            ], 422);
        }

        return response()->json([
            "message" => "قائمة الأسئلة",
            "data" => $faqs
        ]);
    }

    public function get_all_for_admin(){
        $faqs  = Faq::with('tag')->orderBy('id', 'DESC')->get();

        if ($faqs == null) {
            return response()->json([
                "message" => "عذرا لا يوجد"
            ], 422);
        }

        return response()->json([
            "message" => "قائمة الأسئلة",
            "data" => $faqs
        ]);
    }

    public function AddQuestion(Request $request){

        $input = $request->all();

        $validator = Validator::make($input, [
            'question' => 'required',
            'tag_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $Faq = Faq::create([
            'question' => $request->question,
            'tag_id' => $request->tag_id,
        ]);
        return response()->json([
            "message" => "تم إرسال السؤال",
            "data" => $Faq
        ]);
    }

    public function AddAnswer(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 422);
        }

        $faq = Faq::find($id);
        if ($faq == null) {
            return response()->json([
                "message" => "عذرا لا يوجد"
            ], 422);
        }
        $faq['answer'] = $request->answer;
        $faq->update();

        return response()->json([
            "message" => "تمت عملية الإجابة على السؤال بنجاح",
            "data" => $faq
        ]);
    }

}
