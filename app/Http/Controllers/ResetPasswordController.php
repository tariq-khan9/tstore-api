<?php

namespace App\Http\Controllers;
use App\Models\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function send_passreset_email(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->email;

        // Check User's Email Exists or Not
        $user = User::where('email', $email)->first();
        if(!$user){
            return response([
                'message'=>'Email doesnt exists',
                'status'=>'failed'
            ], 404);
        }

        // Generate Token
        $token = Str::random(60);

        // Saving Data to Password Reset Table
        ResetPassword::create([
            'email'=>$email,
            'token'=>$token,
            'created_at'=>Carbon::now()
        ]);
        
        // Sending EMail with Password Reset View
       //Mail::to($email)->send(new SendMail());
      Mail::send('emails.resetpassword', ['token'=>$token], function(Message $message)
      use($email){
        $message->subject('Reset your password');
        $message->to($email);
      });
        return response([
            'message'=>'Password Reset Email Sent... Check Your Email',
            'status'=>'success'
        ], 200);
    }

    public function reset(Request $request, $token){
        // Delete Token older than 5 minute
        $formatted = Carbon::now()->subMinutes(5)->toDateTimeString();
        ResetPassword::where('created_at', '<=', $formatted)->delete();

        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $passwordreset = ResetPassword::where('token', $token)->first();

        if(!$passwordreset){
            return response([
                'message'=>'Token is Invalid or Expired, Kindly resend your Email.',
                'status'=>'failed'
            ], 201);
        }

        $user = User::where('email', $passwordreset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token after resetting password
        ResetPassword::where('email', $user->email)->delete();

        return response([
            'message'=>'Password Reset Success',
            'status'=>'success'
        ], 200);
    }
}
