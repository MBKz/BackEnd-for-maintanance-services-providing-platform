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

  
    public function get_all()
    {
        $jobs  = Job::get();

        if ($jobs == null) {
            return response()->json([
                "message" => "Not Found Job"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Jobs List",
            "data" => $jobs
        ]);
    }


    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'icon' => 'required|image|mimes:png,jpg,jpeg',
            'image' => 'required|image|mimes:png,jpg,jpeg',


        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

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
            "success" => true,
            "message" => "Job created successfully.",
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
            return response()->json([
                "message" => "Not Found job"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Job retrieved successfully.",
            "data" => $job
        ]);
    }


    public function update(Request $request,$id)
    {

        $job = Job::find($id);

        

        $validator = Validator::make($request->all(), [
            'icon' => 'image|mimes:png,jpg,jpeg',
            'image' => 'image|mimes:png,jpg,jpeg',


        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

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

        if ($request->title != null)  $job['title'] = $request->title;
        if ($request->description != null)   $job['description'] = $request->description;
        if ($request->icon != null)       $job['icon'] = $icon;
        if ($request->image != null)       $job['image'] = $image;


        $job->update();

        return response()->json([
            "success" => true,
            "message" => "Job updated successfully.",
            "data" => $job
        ]);
    }

   

    public function destroy($id)
    {
        $job = Job::where('id', $id)->first();

        if ($job == null) {
            return response()->json([
                "message" => "Not Found Job"
            ], 422);
        }
        $job->delete();

        return response()->json([
            "success" => true,
            "message" => "Job deleted successfully ",
            "data" => $job
        ]);
    }
}
