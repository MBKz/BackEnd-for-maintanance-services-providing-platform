<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class HelperController {

    public static function upload_image($image, $path) {

        if(config('app.upload') == 'cloudinary')    return self::cloudinary_upload($image, $path);
        else return self::locally_upload($image, $path);
    }

    public static function locally_upload($image, $path){

        $filename = time() . $image->getClientOriginalName();
        Storage::disk('public')->putFileAs(
            $path,
            $image,
            $filename
        );
        return   url('/') . '/storage/' . $path  . $filename;
    }

    public static function cloudinary_upload($image, $path){

        try {
            return Cloudinary::upload($image->getRealPath(), ['folder' => $path])->getSecurePath();
        }catch (\Exception ){}
    }

    public static function cloudinary_delete($url){

        $link = explode('/' , $url);
        $size = count($link);
        $publicId = $link[$size-3] . '/' . $link[$size-2] . '/' . explode('.',$link[$size-1])[0];
        try {
            return Cloudinary::destroy($publicId);
        }catch (\Exception $e){}
    }

}
