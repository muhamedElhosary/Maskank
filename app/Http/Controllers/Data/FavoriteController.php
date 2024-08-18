<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Post;


class FavoriteController extends Controller
{
    public function index()
    {
        $favorite = Favorite::latest()->get();

        return FavoriteResource::collection($favorite);
    }


    public function store(Request $request , string $post_id)
    {


        $request->validate([
        'renter_id' => 'required|exists:renters,id',
    ]);


    $post = Post::find($post_id);

    if (!$post) {
        return response()->json([
            'message' => 'Post not found',
            'status' => false,
        ], 404);
    }


    $fav = Favorite::create([
        'renter_id' => $request->renter_id,
        'post_id' => $post->id,
    ]);

    if ($fav) {
        return response()->json([
            'message' => 'You favorite this post',
            'status' => true,
            'data' => [
                'favorite' =>new FavoriteResource($fav),

            ],
        ], 200);
    } else {
        return response()->json([
            'message' => 'Failed to favorite the post',
            'status' => false,
        ], 500);
    }
}

public function show($favorite_id)

{
    $favorite = Favorite::find($favorite_id);
    return new FavoriteResource($favorite);
}


public function showFavoritePosts($renter_id)
    {
        try{
            $fav = Favorite::where('renter_id', $renter_id)->get();

            if ($fav->isEmpty()) {
                return response()->json([
                    'message' => 'No favorite posts found for you',
                    'data' => [],
                ]);
            }

            return response()->json([
                'message' => 'These are the favorite posts',
                'data' => FavoriteResource::collection($fav),
            ]);
        }
        catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], 422);
        }

    }

public function destroy($post_id)

    {
        $fav = Favorite::where('post_id',$post_id)->first();

    if (!$fav) {
        return response()->json([
            'message' => 'Favorite  not found.',
        ], 404);
    }

    $post = $fav->post;

    if ($fav->delete()) {
        return response()->json([
            'message' => 'Favorite entry deleted successfully.',
            'data' => [],
        ]);
    }

    return response()->json([
        'message' => 'Failed to delete favorite ',
    ], 500);


    }

   


}
