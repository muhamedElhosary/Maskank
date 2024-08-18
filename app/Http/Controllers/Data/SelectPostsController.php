<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class SelectPostsController extends Controller
{
    public function index(){
        $posts = Post::all();
        return response()->json(PostResource::collection($posts));
    }

     public function postsnumber()
    {
        $posts=count(Post::all());
        return response()->json('posts Number : '. $posts);
    }



    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }


        return response()->json(['post details' => new PostResource($post)]);
    }

    public function filterPosts(Request $request)
    {
        $criteria = $request->all();



        $filteredPosts = $this->filterPostsFromDatabase($criteria);

        return response()->json(['filteredPosts' => PostResource::collection($filteredPosts)]);
    }

    private function filterPostsFromDatabase($criteria)
{
    $query = Post::query();

    if (isset($criteria['description'])) {
        $query->where('description', $criteria['description']);
    }

    if (isset($criteria['price'])) {
        $query->where('price', $criteria['price']);
    }

    if (isset($criteria['bedrooms'])) {
        $query->where('bedrooms', $criteria['bedrooms']);
    }

    if (isset($criteria['bathrooms'])) {
        $query->where('bathrooms', $criteria['bathrooms']);
    }

    if (isset($criteria['size'])) {
        $query->where('size', $criteria['size']);
    }

    if (isset($criteria['condition'])) {
        $query->where('condition', $criteria['condition']);
    }

    if (isset($criteria['city'])) {
        $query->where('city', $criteria['city']);
    }

    $filteredPosts = $query->get();

    // for($i=0; $i<count($filteredPosts); $i++){ $post[]=$filteredPosts[$i]; }
    return $filteredPosts;
}

public function showRandomPosts(Request $request)
{

    $numberOfPosts = $request->input('limit', 5);
    $posts = Post::inRandomOrder()->take($numberOfPosts)->get();
    // for($i=0; $i<count($posts); $i++){ $post[]=$posts[$i]; }
    return response()->json(PostResource::collection($posts));
}

public function waiting()
    {
       $waiting=[];
       $allPosts=Post::all();
       foreach($allPosts as $column)
       {
        if($column->status==0)
        {
            array_push($waiting,$column);

        }

       }

       return response()->json(['data'=>PostResource::collection($waiting)]);


    }


    public function acceptable()
    {
       $accept=[];
       $allPosts=Post::all();
       foreach($allPosts as $column)
       {
        if($column->status!=null)
        {
            array_push($accept,$column);

        }

    }
    return response()->json(['data'=>PostResource::collection($accept)]);
    }


    public function numberOfWaitingAndDone(Request $request, $owner_id)
    {
        $waiting = DB::table('posts')->where('owner_id', $owner_id)->where('status', 0)->count();


        $done = DB::table('posts')->where('owner_id', $owner_id)->where('status', 1)->count();

        return response()->json(['message' => 'Number of Waiting and Done posts', 'Waiting' => $waiting , 'Done' => $done]);
    }



    public function searchLocal(Request $request){
        $term = $request->query('term','');
        $posts = Post::search($term)->get();
        return response()->json(PostResource::collection($posts));
    }


    public function postsOwner($owner_id){
        $posts = Post::where('owner_id',$owner_id)->get();
        return response()->json(['Post' => PostResource::collection($posts)]);

    }








}
