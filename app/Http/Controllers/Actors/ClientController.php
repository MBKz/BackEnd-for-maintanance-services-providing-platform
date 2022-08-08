<?php


namespace App\Http\Controllers\Actors;

use App\Http\Controllers\Controller;
use App\Http\Interface\Actors\ClientInterface;
use App\Models\Client;

class ClientController extends Controller implements ClientInterface
{

    public function get_all()
    {
        $client  = Client::with('user')->get();

        if ($client == null) {
            return response()->json([
                "message" => "هذا العميل غير موجود"
            ], 422);
        }

        return response()->json([
            "message" => "قائمة الزبائن",
            "data" => $client
        ]);
    }

}
