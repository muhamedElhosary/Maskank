<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Renter;
use Illuminate\Validation\ValidationException;

use App\Http\Resources\RequestRenterResource;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Models\RequestRenter;
use App\Models\Post;
class RequestController extends Controller
{

    public function index()
    {
        $renterRequest = RequestRenter::latest()->get();

        return RequestRenterResource::collection($renterRequest);
    }



    public function show($request_id)

{
    $renterRequest = RequestRenter::find($request_id);
    return new RequestRenterResource($renterRequest);
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



    $req = RequestRenter::create([
        'renter_id' => $request->renter_id,
        'post_id' => $post->id,
    ]);

    if ($req) {
        return response()->json([
            'message' => 'You request this post',
            'status' => true,
            'data' => [
                'Request' =>new RequestRenterResource($req),

            ],
        ], 200);
    } else {
        return response()->json([
            'message' => 'Failed to request the post',
            'status' => false,
        ], 500);
    }
}

public function showRequestPosts($renter_id)
    {
        try{
            $renterRequest = RequestRenter::where('renter_id', $renter_id)->get();

            if ($renterRequest->isEmpty()) {
                return response()->json([
                    'message' => 'No request posts found for you',
                    'data' => [],
                ]);
            }

            return response()->json([
                'message' => 'These are the request posts',
                'data' => RequestRenterResource::collection($renterRequest),
            ]);
        }
        catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], 422);
        }

    }

    public function destroy($post_id)

    {
        $renterRequest = RequestRenter::where('post_id',$post_id)->first();

    if (!$renterRequest) {
        return response()->json([
            'message' => 'request  not found.',
        ], 404);
    }

    $post = $renterRequest->post;

    if ($renterRequest->delete()) {
        return response()->json([
            'message' => 'request entry deleted successfully.',
            'data' => [],
        ]);
    }

    return response()->json([
        'message' => 'Failed to delete request ',
    ], 500);


    }



    public function countRequest(){
        $count = RequestRenter::all()->count();
        return response()->json('count : '.$count);

    }

    public function showBooked($renter_id)
{
    $request = RequestRenter::where('renter_id', $renter_id)->get();
    if (!$request) {
        return response()->json(['message' => 'Request not found'], 404);
    }
    // dd($request);
    for($i=0 ; $i<count($request); $i++){
        $post_id = $request[$i]->post_id;
        $post[] = Post::find($post_id);
        $renter = Renter::find($renter_id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }
    return response()->json(['Post' => PostResource::collection($post) , 'Renter' => $renter]);
}

public function OwnerRequest($owner_id){

    $postsWithRequestsAndRenters = Post::where('owner_id', $owner_id)
    ->whereHas('requests', function ($query) {
        $query->whereNotNull('renter_id');
    })
    ->with(['requests.renter'])
    ->get()->map(function ($post) {
        return [
            'post' => new PostResource($post),
            'requests' => $post->requests,
        ];
    });

    return $postsWithRequestsAndRenters;


}


public function updateStatus(Request $request,$request_id)
{
    $validatedData = $request->validate([
        'status' => 'required|integer|between:0,4'
    ]);


    $RequestRenter = RequestRenter::find($request_id);
    if (!$RequestRenter) {
        return response()->json([
            'message' => 'Renter Request not found',
        ], 404);
    }

    $RequestRenter->status = $validatedData['status'];
    $RequestRenter->save();

    return response()->json([
        'message' => 'Post status updated to accepted',

    ],200);





}

}
