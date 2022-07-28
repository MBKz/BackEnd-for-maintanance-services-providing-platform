<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'rate' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $review = Review::create([
            'rate' => $request->rate,
            'comment' => $request->comment,
        ]);
        return response()->json([
            "message" => "تم إضافة تقييم",
            "data" => $review
        ]);
    }

}
