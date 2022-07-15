<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Interface\Posts\PostInterface;
use App\Models\Post;
use App\Models\PostsGallery;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller implements PostInterface
{
    public function get_all()
    {
        $posts  = Post::with('posts_gallery', 'service_provider')->get();

        if ($posts == null) {
            return response()->json([
                "message" => "Not Found Post"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Posts List",
            "data" => $posts
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
            return response(['errors' => $validator->errors()->all()], 422);
        }


        $user_id = auth()->user()->id;
        $service_provider_id = ServiceProvider::where('user_id', $user_id)->first();

        $post = Post::create([
            'text' => $request->text,
            'date' => $request->date,
            'service_provider_id' => $service_provider_id->id,
        ]);


        $images = $request->image;
        if($request->image !=null){
        $echImages[count($images)] = null;
        

        for ($i = 0; $i < count($images); $i++) {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . $image[$i]->getClientOriginalName();
                Storage::disk('public')->putFileAs(
                    'ServiceProvider/Posts',
                    $image[$i],
                    $filename
                );
                $image[$i] = $request->image = url('/') . '/storage/' . 'ServiceProvider' . '/' . 'Posts' . '/' . $filename;
                $echImages[$i] = $image[$i];
            } else {
                $image[$i] = null;
      
            }
        }
    


        

        for ($i = 0; $i < count($image); $i++) {
            
            PostsGallery::create([
                'title' => $request->title,
                'image' => $echImages[$i],
                'post_id' => $post->latest('id')->first()->id,
            ]);
        }
    }


        return response()->json([
            "success" => true,
            "message" => "Post created successfully.",
            "data" => $post->posts_gallery
        ]);
    }

    public function show()
    {
        $user_id = Auth::id();
        $service_provider = ServiceProvider::where('user_id', $user_id)
            ->first();

        $service_provider_id = $service_provider->id;

        $post = Post::with('posts_gallery', 'service_provider')
            ->where('service_provider_id', $service_provider_id)
            ->get();

        if ($post == null) {
            return response()->json([
                "message" => "Not Found Post"
            ], 422);
        }

        return response()->json([
            "success" => true,
            "message" => "Post retrieved successfully.",
            "data" => $post
        ]);
    }



    public function destroy($id)
    {
        $post = Post::where('id', $id)->first();

        if ($post == null) {
            return response()->json([
                "message" => "Not Found Post"
            ], 422);
        }
        $post->delete();

        return response()->json([
            "success" => true,
            "message" => "Post deleted successfully ",
            "data" => $post
        ]);
    }
}
