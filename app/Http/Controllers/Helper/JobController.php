<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Http\Interface\Helper\JobInterface;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class JobController extends Controller implements JobInterface
{

  // TODO: upload func

    public function get_all()
    {
        $jobs  = Job::get();
        return response()->json([
            "message" => "قائمة الخدمات",
            "data" => $jobs
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'icon' => 'required|image|mimes:png,jpg,jpeg,bmp',
            'image' => 'required|image|mimes:png,jpg,jpeg,bmp',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // TODO: upload func
        if ($request->hasFile('icon') && ($request->hasFile('icon')) != null) {
            $icon = $request->file('icon');
            $filename = time() . $icon->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'Job/icon',
                $icon,
                $filename
            );
            $icon = $request->icon = url('/') . '/storage/' . 'Job' .'/' . 'icon' . '/' . $filename;
        } else
            $icon = null;

            if ($request->hasFile('image') && ($request->hasFile('image')) != null) {
                $image = $request->file('image');
                $filename = time() . $image->getClientOriginalName();
                Storage::disk('public')->putFileAs(
                    'Job/image',
                    $image,
                    $filename
                );
                $image = $request->image = url('/') . '/storage/' . 'Job' . '/' . 'image' . '/' . $filename;
            } else
                $image = null;


        $job = Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $icon,
            'image' => $image,
        ]);
        return response()->json([
            "message" => "تمت إضافة خدمة جديدة",
            "data" => $job
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::find($id);
        if ($job == null) {
            return response(["error" => "عذرا غير موجود"], 404);
        }
        return response(["message" => "معلومات الخدمة", "data" => $job]);
    }

    public function update(Request $request,$id)
    {

        $job = Job::find($id);
        $validator = Validator::make($request->all(), [
            'icon' => 'image|mimes:png,jpg,jpeg,bmp',
            'image' => 'image|mimes:png,jpg,jpeg,bmp',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // TODO: upload func
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $filename = time() . $icon->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'Job/icon',
                $icon,
                $filename
            );
            $icon = $request->icon = url('/') . '/storage/' . 'Job' .'/' . 'icon' . '/' . $filename;
        } else
            $icon = null;

            if ($request->hasFile('image') && ($request->hasFile('image')) != null) {
                $image = $request->file('image');
                $filename = time() . $image->getClientOriginalName();
                Storage::disk('public')->putFileAs(
                    'Job/image',
                    $image,
                    $filename
                );
                $image = $request->image = url('/') . '/storage/' . 'Job' . '/' . 'image' . '/' . $filename;
            } else
                $image = null;

        if ($request->title != null)  $job['title'] = $request->title;
        if ($request->description != null)   $job['description'] = $request->description;
        if ($request->icon != null)       $job['icon'] = $icon;
        if ($request->image != null)       $job['image'] = $image;


        $job->update();

        return response()->json([
            "message" => "تمت عملية التعديل بنجاح",
            "data" => $job
        ]);
    }

    public function destroy($id)
    {
        $job = Job::firstWhere('id', $id);
        if ($job == null) {
            return response()->json([
                "error" => " عذرا غير موجود"
            ], 404);
        }
        $job->delete();
        return response()->json([
            "message" => "تمت عملية الحذف بنجاح",
            "data" => $job
        ]);
    }
}
