<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\Owner;
use App\Models\Renter;

class ResetPasswordController extends Controller
{
    private $otp;
    public function __construct(){
        $this->otp = new Otp;
    }
    public function passwordResetOwner(ResetPasswordRequest $request){
        $otp2 = $this->otp->validate($request->email,$request->otp);

        if(! $otp2->status){
            return response()->json(['error'=> $otp2 ],401);
        }

        $user = Owner::where('email',$request->email)->first();
        $user->update(['password'=>Hash::make($request->password)]);
        $user->tokens()->delete();

        $success['success']=true;
        $success['message']='you Reset password';
        return response()->json([$success],200);
    }
    public function passwordResetRenter(ResetPasswordRequest $request){
        $otp2 = $this->otp->validate($request->email,$request->otp);

        if(! $otp2->status){
            return response()->json(['error'=> $otp2 ],401);
        }

        $user = Renter::where('email',$request->email)->first();
        $user->update(['password'=>Hash::make($request->password)]);
        $user->tokens()->delete();

        $success['success']=true;
        $success['message']='you Reset password';
        return response()->json([$success],200);

    }
    public function ChangePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        // dd($user);

        // Check if the old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'The provided password does not match your current password.'], 403);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully.']);
    }

}
