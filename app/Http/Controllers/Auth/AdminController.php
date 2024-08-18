<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function login(Request $request)
    {
         $request->validate([
            "username" => "required|string",
            "password" => "required|",
        ]);

        $admin=Admin::where("username",$request->input("username"))->first();
          if(!$admin)
          {
            return response()->json(["message"=>"admin not found"],401);
          }
            if(!Hash::check($request->input("password") , $admin->password))
            {
                return response()->json(["message"=>"wrong password"],404);
            }
            $token = $admin->createToken("Maskank",['admin']);
            return response()->json(["token" => $token->plainTextToken ,
            "message" => "Admin successfully logged in!"
        ] , 200);
    }


    public function logout(Request $request)
    {

    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logout successful'], 200);
    }


}
