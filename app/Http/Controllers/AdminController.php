<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admin;


class AdminController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed',
           
        ]);
        if(Admin::where('email', $request->email)->first()){
            return response([
                'message' => 'Email exists already ',
                'status'=>'failed'
            ], 200);
        }

        $admin = Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
          
        ]);
        $token = $admin->createToken($request->email)->plainTextToken;
        return response([
            'token'=>$token,
            'message' => 'You Successfully Registrated',
            'status'=>'success'
        ], 201);

    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
        $admin = Admin::where('email', $request->email)->first();
        if($admin && Hash::check($request->password, $admin->password)){
            $token = $admin->createToken($request->email)->plainTextToken;
            $email = $request->email;
            $name = $admin->name;
            return response([
                'name' =>$name,
                'email'=>$email,
                'token'=>$token,
                'message' => 'Login Success',
                'status'=>'success'
            ], 200);
        }
        else{
        return response([
            'message' => 'The Provided Credentials are incorrect',
            'status'=>'failed'
        ], 200);
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success',
            'status'=>'success'
        ], 201);
    }

    public function change_password(Request $request){
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $loggeduser = auth()->user();
        $loggeduser->password = Hash::make($request->password);
        $loggeduser->save();
        return response([
            'message' => 'Password Changed Successfully',
            'status'=>'success'
        ], 200);
    }

}
