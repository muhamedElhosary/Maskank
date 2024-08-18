<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Data\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Owner;
use App\Models\Post;
use Exception;

use Illuminate\Http\Request;

class UpdatePostController extends Controller
{
    public function update(UpdatePostRequest $request ,$post_id){


        try {

            $post = Post::find($post_id);
            if(!$post){
                return response()->json(['message'=>'Post not found'],404);
            }


            $data = $request->validated();



            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $imageName='';
                foreach($images as $img){
                $ext = $img->getClientOriginalExtension();
                $newName = time().'_'.rand().'.'.$ext;
                $fullImage =  $img->move(public_path().'/imagesPosts', $newName);
                $newName = explode('\\',$fullImage);
                $imageName =$imageName.end($newName).',';

                    }

                if (!empty($imageName)) {
                    $imagesPost = explode(',', $post->images);

                    foreach ($imagesPost as $image) {
                        if (!empty($image)) {
                        $imagePath = public_path('imagesPosts/' . $image);
                            if (file_exists($imagePath)) {
                            unlink($imagePath);
                                }
                        }
                    }


                    $data['images'] = $imageName;
                }
            }

            $post->update($data);

            return response()->json(['message' => 'Post updated successfully', 'post' => new PostResource($post)], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }




    }

    public function updateStatus(Request $request, $post_id)
    {

        $validatedData = $request->validate([
            'status' => 'required|integer|between:0,9'
        ]);


        $post = Post::find($post_id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

         $owner = Owner::find($post->owner_id);




        $post->status = $validatedData['status'];
        $post->save();
        if($owner->status == 0){
            $owner->status = 1;
            $owner->save();
        }

        return response()->json([
            'message' => 'Post status updated successfully',
            'post' => new PostResource($post)
        ]);
    }

}


