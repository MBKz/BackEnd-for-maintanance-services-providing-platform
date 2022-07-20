<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function order_confirm(Request $request)
    {
        $input = $request->all();

        
        $validator = Validator::make($input, [
            'proposal_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $order = Order::create([
            'proposal_id' => $request->proposal_id,
            'state_id' => 1,
        ]);

        $order->proposal()->update([
            'state_id' => 2,
        ]); 

        $order->proposal->initial_order()->update([
            'state_id' => 4,
        ]); 

        return response()->json([
            "message" => "تم ارسال عرض الصيانة بنجاح",
            "data" => $order
        ]);
 
    }
}
