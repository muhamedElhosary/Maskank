<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\OwnerResource;
use App\Models\Owner;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\createOwnerRequest;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Auth\EmailVerificationNotification;
use Illuminate\Validation\ValidationException;






class OwnerController extends Controller
{
    public function store(createOwnerRequest $request)
    {
        try {

            $data = $request->validated();

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');

                if (!$photo->isValid()) {
                    throw new \Exception('Invalid photo upload.');
                }


                $imageName = time() . '_' . rand() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('OwnerPhoto'), $imageName);
                $data['photo'] = $imageName;
            } else {

                $data['photo'] = "user.jpg";
            }


            $owner = Owner::create($data);


            // $owner->notify(new EmailVerificationNotification());


            $success['message'] = "Registration successful";
            $success['data'] = new OwnerResource($owner);

            return response()->json($success);
        } catch (ValidationException $e) {

            return response()->json(['error' => $e->validator->errors()->all()], 422);
        } catch (\Illuminate\Database\QueryException $e) {

            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            \Log::error('Error storing owner: ' . $e->getMessage());
            return response()->json(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }



    public function login(Request $request)
    {
        $owner = Owner::where("username", $request->input("username"))->first();
        if (!$owner) {
            return response()->json([
                "message" => "owner not found"
            ], 401);
        }
        if (!Hash::check($request->input("password"), $owner->password)) {
            return response()->json([
                "message" => "wrong password"
            ], 401);
        }
        // if ($owner['email_verified_at'] == null) {
        //     return response()->json([
        //         'status' => 'failed',
        //         "message" => "please verify your email and try again",
        //         'code' => 401,
        //     ], 401);
        // }
        $token = $owner->createToken("Maskank",['owner']);


        return response()->json([
            'status' => 'success',
            "token" => $token->plainTextToken,
            "message" => "owner successfully logged in",
            "owner" => new OwnerResource($owner)
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(["message" => "owner successfully logged out"], 200);
        } catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], 422);
        }
    }


}
