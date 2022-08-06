<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HelperController {

    function upload_image_localy(Request $request, $image_name, $path) {

        if ($request->hasFile($image_name)) {
            $image = $request->file($image_name);
            $filename = time() . $image->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                $path,
                $image,
                $filename
            );
         return   $image = $request->image = url('/') . '/storage/' . $path  . $filename;
        } else
        return   $image = null;
    }

    function upload_array_of_images_localy(Request $request, $image_name, $path, $count) {

        if ($request->hasFile($image_name)) {
            $image = $request->file($image_name);
            $filename = time() . $image[$count]->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                $path,
                $image[$count],
                $filename
            );
         return   $image = $request->image = url('/') . '/storage/' . $path  . $filename;
        } else
        return   $image = null;
    }
}
