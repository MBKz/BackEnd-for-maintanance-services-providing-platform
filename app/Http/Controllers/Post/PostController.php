<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Interface\Posts\PostInterface;
use App\Models\Post;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'date' => 'required|date|date_format:Y/m/d',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $post = Post::create([
            'text' => $request->text,
            'date' => $request->date,
            'service_provider_id' => auth()->user()->id,
        ]);
        return response()->json([
            "success" => true,
            "message" => "Post created successfully.",
            "data" => $post
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


    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if ($post == null) {
            return response()->json([
                "message" => "Not Found Post"
            ], 422);
        }

        $validator = Validator::make($request->all, [
            'date' => 'date',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->text != null)  $post['text'] = $request->text;
        if ($request->date != null)  $post['date'] = $request->date;


        $post->update();

        return response()->json([
            "success" => true,
            "message" => "Post updated successfully.",
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
