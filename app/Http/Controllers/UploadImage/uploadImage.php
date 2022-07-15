<?php

namespace App\Http\Controllers\UploadImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

function upload(Request $request, $image) {

    if ($request->hasFile('image') && ($request->hasFile('image')) != null) {
        $image = $request->file('image');
        $filename = time() . $image->getClientOriginalName();
        Storage::disk('public')->putFileAs(
            'UserPhoto/ServiceProviderProfile/IdentityPhoto',
            $image,
            $filename
        );
        $image = $request->image = url('/') . '/storage/' . 'UserPhoto' . '/' . 'ServiceProviderProfile' . '/' . 'IdentityPhoto' . '/' . $filename;
    } else
        $image = null;
}