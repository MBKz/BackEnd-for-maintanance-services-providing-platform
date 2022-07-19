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
        return response()->json([
            "message" => "معلومات الشركة",
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
            'description' => 'required',
            'policy' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $Company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'description' => $request->description,
            'policy' => $request->policy,
        ]);
        return response()->json([
            "message" => "تم إنشاء الشركة بنجاح",
            "data" => $Company
        ]);
    }

    public function show($id)
    {
        $Company = Company::find($id);

        if ($Company == null) {
            return response()->json([
                "message" => "عذرا"
            ], 404);
        }

        return response()->json([
            "message" => "معلومات الشركة",
            "data" => $Company
        ]);
    }

    public function update(Request $request, $id)
    {
        $Company = Company::find($id);

        if ($Company == null) {
            return response()->json([
                "message" => "عذرا"
            ], 404);
        }

        if ($request->name != null)  $Company['name'] = $request->name;
        if ($request->email != null)  $Company['email'] = $request->email;
        if ($request->phone_number != null)  $Company['phone_number'] = $request->phone_number;
        if ($request->description != null)  $Company['description'] = $request->description;
        if ($request->policy != null)  $Company['policy'] = $request->policy;

        $Company->update();

        return response()->json([
            "message" => "تم التعديل",
            "data" => $Company
        ]);
    }

    public function destroy($id)
    {
        $Company = Company::where('id', $id)->first();

        if ($Company == null) {
            return response()->json([
                "message" => "عذرا"
            ], 422);
        }
        $Company->delete();

        return response()->json([
            "message" => "تم حذف الشركة",
            "data" => $Company
        ]);
    }
}
