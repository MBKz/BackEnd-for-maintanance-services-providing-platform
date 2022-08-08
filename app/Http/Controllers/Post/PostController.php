<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Interface\Posts\PostInterface;
use App\Models\Client;
use App\Models\Post;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller implements PostInterface
{
    public function provider_info($id)
    {

        $povider  = ServiceProvider::with('user', 'post', 'post.posts_gallery')
            ->where('id', $id)
            ->get();

        return response()->json([
            "message" => "معلومات المزود",
            "data" => $povider
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'text' => 'required',
            'date' => 'required',
            'image[]' => 'array|image|mimes:jpg,png,jpeg,gif,svg',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 422);
        }


        $service_provider = ServiceProvider::where('user_id', auth()->user()->id)->first();

        $post = $service_provider->post()->create([
            'text' => $request->text,
            'date' => $request->date,
        ]);

        $upload = new HelperController();

        $images = $request->image;
        if ($request->image != null) {

            for ($i = 0; $i < count($images); $i++) {

                $image =  $upload->upload_array_of_images_localy($request, 'image', 'ServiceProvider/Posts/', $i);

                $post->posts_gallery()->create([
                    'title' => $request->title,
                    'image' => $image,
                ]);
            }
        }


        return response()->json([
            "message" => "تمت عملية النشر بنجاح",
            "data" => $post->load('posts_gallery')
        ]);
    }

    public function show()
    {

        $service_provider = ServiceProvider::where('user_id', Auth::id())
            ->first();

        $post = Post::with('posts_gallery')
            ->where('service_provider_id', $service_provider->id)
            ->get();

        return response()->json([
            "message" => "قائمة المنشورات",
            "data" => $post
        ]);
    }

    public function destroy($id)
    {

        $post = Post::with('posts_gallery')->firstWhere('id', $id);
        if ($post == null) {
            return response()->json([
                "message" => "غير موجود"
            ], 404);
        }

        $photos = $post->posts_gallery;
        foreach ($photos as $photo) {
            $photo->delete();
        }
        $post->delete();

        return response()->json([
            "message" => "تم حذف المنشور",
        ]);
    }
}
