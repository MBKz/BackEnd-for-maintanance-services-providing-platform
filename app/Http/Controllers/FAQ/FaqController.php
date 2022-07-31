<?php

namespace App\Http\Controllers\FAQ;

use App\Http\Controllers\Controller;
use App\Http\Interface\FAQ\FAQInterface;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller implements FAQInterface
{

    public function get_all(){

        $faqs  = Faq::with('tag')->where('answer' ,'!=' ,'null')->get();

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
            return response(['errors' => $validator->errors()->all()], 422);
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

    public function backup(){

        $result = Artisan::call('backup:run');
//        dd(Artisan::output());
        if($result == 1)
            return response(['message'=>'فشلت عملية النسخ الاحتياطي , الرجاء المحاولة مرة أخرى','date'=>now() ]);
        return response(['message'=>'تمت عملية النسخ الاحتياطي بنجاح','date'=>now()]);
    }
}
