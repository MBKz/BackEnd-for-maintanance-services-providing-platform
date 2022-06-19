<?php

namespace App\Http\Controllers\SysInfo;

use App\Http\Controllers\Controller;
use App\Http\Interface\SysInfo\SysInfoInterface;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller implements SysInfoInterface
{
    public function get_all()
    {
        $Company  = Company::get();

        if ($Company == null) {
            return response()->json([
                "message" => "Not Found Company"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Company List",
            "data" => $Company
        ]);
    }



    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'describtion' => 'required',
            'policy' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $Company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'describtion' => $request->describtion,
            'policy' => $request->policy,
        ]);
        return response()->json([
            "success" => true,
            "message" => "Company created successfully.",
            "data" => $Company
        ]);
    }

    public function show($id)
    {
        $Company = Company::find($id);

        if ($Company == null) {
            return response()->json([
                "message" => "Not Found Company"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Company retrieved successfully.",
            "data" => $Company
        ]);
    }


    public function update(Request $request, $id)
    {
        $Company = Company::find($id);

        if ($Company == null) {
            return response()->json([
                "message" => "Not Found Company"
            ], 422);
        }

        if ($request->name != null)  $job['name'] = $request->name;
        if ($request->email != null)  $job['email'] = $request->email;
        if ($request->phone_number != null)  $job['phone_number'] = $request->phone_number;
        if ($request->describtion != null)  $job['describtion'] = $request->describtion;
        if ($request->policy != null)  $job['policy'] = $request->policy;

        $Company->update();

        return response()->json([
            "success" => true,
            "message" => "Company updated successfully.",
            "data" => $Company
        ]);
    }


    public function destroy($id)
    {
        $Company = Company::where('id', $id)->first();

        if ($Company == null) {
            return response()->json([
                "message" => "Not Found Company"
            ], 422);
        }
        $Company->delete();

        return response()->json([
            "success" => true,
            "message" => "Company deleted successfully ",
            "data" => $Company
        ]);
    }
}
