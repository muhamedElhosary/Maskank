<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\RenterResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\createRenterRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use App\Models\Renter;
use App\Notifications\Auth\EmailVerificationNotification;
// use Ichtrojan\Otp\Otp;

class RenterController extends Controller
{
    public function store(createRenterRequest $request){

        try {
            $data = $request->validated();

            if (!empty($data['photo'])) {
                $ext = $data['photo']->getClientOriginalExtension();
                $imageName = time() . '_' . rand() . '.' . $ext;
                $photoName = $data['photo']->move(public_path() . '/RenterPhoto', $imageName);
                $newName = explode('\\', $photoName);
                $data['photo'] = end($newName);
            } else {
                $data['photo'] = "user.jpg";
            }

            $renter = Renter::create($data);
            // $renter->notify(new EmailVerificationNotification());

            return response()->json(["message" => "Registration successful", "data" => new RenterResource($renter)], 200);
        } catch (ValidationException $e) {
            return response()->json(["message" => "Validation failed", "errors" => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(["message" => "Database error", "error" => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(["message" => "An unexpected error occurred", "error" => $e->getMessage()], 500);
        }
    }

    public function login(Request $request){
        $renter =Renter::where("username" , $request->input("username"))->first();
        if(!$renter){
            return response()->json(["message"=> "user not found"] , 401);
        }
        if(!Hash::check($request->input("password") , $renter->password)){
            return response()->json(["message" => "wrong password"] , 401);
        }
       $token = $renter->createToken("Maskank" , ['renter']);
        return response()->json(["token" => $token->plainTextToken ,
        "message" => "renter successfully logged in" , "data"=> new RenterResource($renter)] , 200);
    }
    public function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json(["message" => "renter successfully logged out"] , 200);
        }
        catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], 422);
        }

    }





}
