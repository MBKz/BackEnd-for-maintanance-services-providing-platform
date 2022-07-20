<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Proposal;
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
            'state_id' => 2,
        ]); 

        $order->proposal->initial_order()->update([
            'state_id' => 4,
        ]);

        $proposal = Proposal::where('id',$id)->first();
     
        $proposals = Proposal::where('initial_order_id',$proposal->initial_order_id)->get();

        foreach($proposals as $one){
            if($one->id != $id) 
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
        $order['state_id']= 3;

        $order->update();

        return response()->json([
            "message" => "تم بدء العمل",
            "data" => $order
        ]);
 
    }

    public function order_end(Request $request,$id)
    {
        $order = Order::find($id);

        $validator = Validator::make($request->all(), ['cost' => 'required']);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $order['end_date'] = now();
        $order['state_id']= 4;
        $order['cost']= $request->cost;

        $order->update();

        $order->proposal()->update([
            'state_id' => 4,
        ]);

        

        return response()->json([
            "message" => "تم انهاء العمل",
            "data" => $order
        ]);
 
    }
}
