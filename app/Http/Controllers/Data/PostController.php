<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Data\CreatePostRequest;
use App\Http\Resources\PostResource;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Post;



use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(CreatePostRequest $request)
    {
        $validatedData = $request->validated();

        $images = $request->file('images');
        $imageName=[];
        foreach($images as $img){
            $ext = $img->getClientOriginalExtension();
            $newName = time().'_'.rand().'.'.$ext;
           $fullImage =  $img->move(public_path().'/imagesPosts', $newName);
            $newName = explode('\\',$fullImage);

            $imageName[] =end($newName);
            }


            $validatedData['images']= implode(',' , $imageName);

        try {
            $post = Post::create($validatedData);

            return response()->json([
                'message' => 'Post created successfully',
                'item' =>new PostResource($post),
            ], 201);
        } catch (QueryException $exception) {
            logger()->error('Failed to create item: ' . $exception->getMessage());
            return response()->json(['message' => 'Failed to create item. Error: ' . $exception->getMessage()], 500);
        }
    }



    // public function updateStatus(Request $request, $post_id)
    // {
    //     $post = Post::findOrFail($post_id);
    //     $post->status = 1;
    //     $post->save();
    //     return response()->json(['message' => 'Post status updated successfully', 'post' => new PostResource($post)]);
    // }



    public function destroy($post_id)
    {
        try {

            $post = Post::find($post_id);
            if( !$post){
                return response()->json(['message' => "Post not found"], 404);
            }

            $imagesPost = explode(',', $post->images);

            foreach ($imagesPost as $image) {
                if (!empty($image)) {
                    $imagePath = public_path('imagesPosts/' . $image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }


            $deleted = $post->delete();


            if ($deleted) {
                return response()->json(['message' => "Post deleted successfully"], 200);
            } else {
                return response()->json(['message' => "Failed to delete post"], 500);
            }
        } catch (Exception $e) {
            return response()->json(['message' => "Error: " . $e->getMessage()], 500);
        }
    }
}
