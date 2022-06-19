<?php

namespace App\Http\Controllers\FAQ;

use App\Http\Controllers\Controller;
use App\Http\Interface\FAQ\FAQInterface;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller implements FAQInterface 
{
    public function get_all(){

        $faqs  = Faq::with('tag')->get();

        if ($faqs == null) {
            return response()->json([
                "message" => "Not Found FAQ"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "FAQ List",
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
            "success" => true,
            "message" => "FAQ created successfully.",
            "data" => $Faq
        ]);
    }
    public function AddAnswer(Request $request,$id){

        $faq = Faq::find($id);

        if ($faq == null) {
            return response()->json([
                "message" => "Not Found FAQ"
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $faq['answer'] = $request->answer;

        $faq->update();

        return response()->json([
            "success" => true,
            "message" => "FAQ updated successfully.",
            "data" => $faq
        ]);
    }
}
