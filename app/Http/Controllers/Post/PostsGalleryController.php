<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Interface\Posts\PostPhotoInterface;
use App\Models\PostsGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostsGalleryController extends Controller implements PostPhotoInterface
{

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
            'post_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . $image->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'ServiceProvider/Posts',
                $image,
                $filename
            );
            $image = $request->image = url('/') . '/storage/' . 'ServiceProvider' . '/' . 'Posts' . '/' . $filename;
        }

        $postsGallery = PostsGallery::create([
            'title' => $request->title,
            'image' => $image,
            'post_id' => $request->post_id,
        ]);
        return response()->json([
            "success" => true,
            "message" => "Posts Gallery created successfully.",
            "data" => $postsGallery
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $postsGallery = PostsGallery::find($id);

        if ($postsGallery == null) {
            return response()->json([
                "message" => "Not Found Posts Gallery"
            ], 422);
        }
        
        
        $validator = Validator::make($request->all, [
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . $image->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'ServiceProvider/Posts',
                $image,
                $filename
            );
            $image = $request->image = url('/') . '/storage/' . 'ServiceProvider' . '/' . 'Posts' . '/' . $filename;
        }

        if ($request->title != null)  $postsGallery['title'] = $request->title;
        if ($request->date != null)  $postsGallery['image'] = $image;
        if ($request->post_id != null)  $postsGallery['post_id'] = $request->post_id;
        

        $postsGallery->update();

        return response()->json([
            "success" => true,
            "message" => "Posts Gallery updated successfully.",
            "data" => $postsGallery
        ]);
    }


    public function destroy($id)
    {
        $postsGallery = PostsGallery::where('id', $id)->first();

        if ($postsGallery == null) {
            return response()->json([
                "message" => "Not Found Posts Gallery"
            ], 422);
        }
        $postsGallery->delete();

        return response()->json([
            "success" => true,
            "message" => "Posts Gallery deleted successfully ",
            "data" => $postsGallery
        ]);
    }
}
