<?php

namespace App\Http\Controllers;

use App\Mail\SendOtp;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function confirmOtpForm()
    {
        if (auth()->user()->otp->count() <= 0) {
            return redirect()->route('home');
        }
        return view('confirm-otp');
    }

    public function validateOtp(Request $request)
    {
        $otp = EmailOtp::where(['user_id' => auth()->user()->id, 'otp' => $request->otp])->first();
        if ($otp != null) {
            auth()->user()->update([
                'password' => Hash::make(session('new_password'))
            ]);
            # delete otp
            $otp->delete();
            return redirect()->route('home');
        }else{
            return back()->withError('Incorrect OTP');
        }
    }

    public function validatePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6']
        ]);

        # check for current password match
        if (password_verify($request->current_password, auth()->user()->password)) {
            # if it returns true, 
            if ($request->new_password == $request->confirm_password) {
                # send email OTP to the user
                # create otp
                $otp = rand(10, 9999);
                # save otp
                EmailOtp::create([
                    'user_id' => auth()->user()->id, 
                    'otp' => $otp
                ]);
                # save new password inside a session
                session(['new_password' => $request->new_password]);
                # send otp
                if (Mail::to(auth()->user()->email)->send(new SendOtp($otp))) {
                    return redirect()->route('confirm.otp');
                }else{
                    dd('an error occured');
                }
                
            }else{
                return back()->withError('Error! password mismatch!');
            }
        }else{
            # if it returns false
            return back()->withError('Error! The password did not match with the current password');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
