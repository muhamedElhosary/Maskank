<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\OwnerResource;
use App\Http\Resources\RenterResource;
use App\Models\Owner;
use App\Models\Renter;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    private $otp;
    public function __construct(){
        $this->otp = new Otp;
    }
    public function  verifyEmailRenter(EmailVerificationRequest $request){

        $otp2 = $this->otp->validate($request->email,$request->otp);


        if(!$otp2->status){
            return response()->json(['error'=>$otp2],401);
        }


        $renter = Renter::where('email',$request->email)->first();
        $renter->update(['email_verified_at'=>now()]);
        $success['success'] = true;
        $success['data']    = new RenterResource($renter);
        $success['message'] = 'you verification Email';
        return response()->json([$success],200);
    }

    public function  verifyEmailOwner(EmailVerificationRequest $request){

        $otp2 = $this->otp->validate($request->email,$request->otp);

        if(!$otp2->status){
            return response()->json(['error'=>$otp2],401);
        }


        $owner = Owner::where('email',$request->email)->first();
        
        $owner->update(['email_verified_at'=>now()]);
        $success['success'] = true;
        $success['data']    = new OwnerResource($owner);
        $success['message'] = 'you verification Email';
        return response()->json([$success],200);
    }

}
