<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\RenterResource;
use App\Models\Renter;
use Illuminate\Http\Request;
use App\Http\Requests\Users\UpdateRenterRequest;

class RentersController extends Controller
{
    public function index(){
        $renters = Renter::all();
        return response()->json(RenterResource::collection($renters));


    }

    public function show($renter_id){
        $renter = Renter::find($renter_id);
        return response()->json(new RenterResource($renter));


    }


    public function update(UpdateRenterRequest $request,$renter_id)
    {
        $renter = Renter::find($renter_id);

        if(!$renter){
            return response()->json([
                'status' =>  false,
                'massage'=>  'not found',
                'data'   =>  null,
            ],404);
        }

        $data = $request->validated();
        if($request->hasFile('photo')){
            if ($renter->photo != 'renter.jpg'){
                $imagePath = public_path('RenterPhoto/'.$renter->photo);
                if (file_exists($imagePath)) {
                unlink($imagePath);
            }
                    $ext = $data['photo']->getClientOriginalExtension();
                    $imageName = time().'_'.rand().'.'.$ext;
                    $photoName =  $data['photo']->move(public_path().'/RenterPhoto', $imageName);
                    $newName = explode('\\',$photoName);
                    $data['photo'] = end($newName);


            }
        }

        if( $renter->update($data)){
            return response()->json([
            'status' =>  true,
            'massage'=>  'Renter update successfully',
            'data'   =>  new RenterResource($renter),
        ],200);
        }else{
        return response()->json([
            'status' => false,
            'massage'=> 'Renter not update successfully',
            'data'   =>  [],
        ],405);
        }
    }


    public function destroy($renter_id){
        $renter = Renter::find($renter_id);
    if (!$renter) {
        return response()->json(['message' => 'Renter not found'], 404);
    }
    if ($renter->photo != 'renter.jpg'){
        $imagePath = public_path('RenterPhoto/'.$renter->photo);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    if($renter->delete()){
        return response()->json(['data'=>[],'status'=>true,'message'=>'renter is deleted successfully'],200);
    }
    }
}
