<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Renter;

class ForgetPasswordController extends Controller
{
    public function forgetPasswordOwner(ForgetPasswordRequest $request){
        $input = $request->only('email');
        $user = Owner::where('email',$input)->first();
        $user->notify(new ResetPasswordNotification());
        $success['success']=true;
        $success['message']='check your email';
        return response()->json([$success],200);
    }
    public function forgetPasswordRenter(ForgetPasswordRequest $request){
        $input = $request->only('email');
        $user = Renter::where('email',$input)->first();
        $user->notify(new ResetPasswordNotification());
        $success['success']=true;
        $success['message']='check your email';
        return response()->json([$success],200);
    }
}
