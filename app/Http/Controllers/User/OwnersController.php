<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateOwnerRequest;
use App\Http\Resources\OwnerResource;
use Illuminate\Http\Request;
use App\Models\Owner;
use Illuminate\Support\Facades\Auth;
class OwnersController extends Controller
{
    public function index(){

        $owners = Owner::all();

        return response()->json(OwnerResource::collection($owners));

    }


    public function show($owner_id){

        $user = Auth::guard('sanctum')->user();
        if(!$user->tokenCan('owner')){
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $owner = Owner::find($owner_id);
        return response()->json(new OwnerResource($owner));


    }

    public function update(UpdateOwnerRequest $request,$owner_id)
    {
        // $user = Auth::guard('sanctum')->user();
        // if(!$user->tokenCan(['owner' , 'admin'])){
        //     return response()->json(['message' => 'Not allowed'], 403);
        // }

        $owner = owner::find($owner_id);
        $data = $request->validated();
            if($request->hasFile('photo')){
                if ($owner->photo != 'owner.jpg'){
                    $imagePath = public_path('OwnerPhoto/'.$owner->photo);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $ext = $data['photo']->getClientOriginalExtension();
                    $imageName = time().'_'.rand().'.'.$ext;
                    $photoName =  $data['photo']->move(public_path().'/OwnerPhoto', $imageName);
                    $newName = explode('\\',$photoName);
                    $data['photo'] = end($newName);
                }
            }

            if( $owner->update($data)){
                return response()->json([
                'status' =>  true,
                'massage'=>  'Owner update successfully',
                'data'   => new OwnerResource($owner),
                'path'=> asset('OwnerPhoto/'.$owner->photo),
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'massage'=> 'owner not update successfully',
                'data'=>  [],
            ],405);
        }
    }



    public function destroy($owner_id)
    {
        $owner = Owner::find($owner_id);
        if (!$owner) {
            return response()->json(['message' => 'Owner not found'], 404);
        }

        if ($owner->photo != 'owner.jpg'){
        $imagePath = public_path('ownerPhoto/'.$owner->photo);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
        $deleted = $owner->delete();
        // $ownerData = Owner::all();
        if ($deleted) {
            $message = 'Owner deleted successfully.';
            return response()->json(['data' => [] , 'status' => true, 'message' => $message], 200);
        } else {
            return response()->json(['message' => 'Failed to delete owner'], 500);
        }
    }

}
