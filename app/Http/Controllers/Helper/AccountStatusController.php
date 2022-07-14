<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Http\Interface\Helper\AccountStatusInterface;
use App\Models\AccountStatus;
use Illuminate\Http\Request;

class AccountStatusController extends Controller implements AccountStatusInterface
{
   
    public function get_all()
    {
        $accountStatus  = AccountStatus::get();

        if ($accountStatus == null) {
            return response()->json([
                "message" => "Not Found Account Status"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Account Status List",
            "data" => $accountStatus
        ]);
    }


}
