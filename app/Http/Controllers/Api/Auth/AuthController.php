<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string|unique:users,email',
            'password'=> 'required|string|confirmed|min:6',
        ]);

        $user=User::create([
            'name'=> $fields['name'],
            'email'=> $fields['email'],
            'password'=> bcrypt($fields['password']) 
        ]);

        $token= $user->createToken('myapptoken')->plainTextToken;
     
        Mail::to($user)->send(new EmailVerification($user));

        $response = [
           'user'=> $user,
           'token'=> $token,
           'token_type' => 'Bearer'
        ];

        return response($response,201);
    }

    public function logout(){
        auth()->user()->tokens()->delete();    
        $response = [
            'message' => 'Logged out'            
         ];
         return response($response,200);
    }

    public function login(Request $request){      
        
        $fields= $request->validate([       
            'email'=> 'required|string|email',
            'password'=> 'required|string',
        ]);

       //Check email:
       $user= User::where('email', $fields['email'])->first();

        //Check password:
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return response([
                'message'=> 'The provided credentials are incorrect!'
            ], 401);
        }

        $token= $user->createToken('myapptoken')->plainTextToken;

        $response = [
           'user'=> $user,
           'token'=> $token,
           'token_type' => 'Bearer'
        ];

        return response($response,200);
    }

}

