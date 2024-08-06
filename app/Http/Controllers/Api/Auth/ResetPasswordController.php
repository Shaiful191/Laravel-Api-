<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class ResetPasswordController extends Controller
{
        //Password Reset:
        public function sendResetLinkEmail(Request $request)
        {
            $fields = $request->validate([
                'email' => ['required', 'email', Rule::exists(User::class, 'email')]
            ]);
    
            $url = URL::temporarySignedRoute('password.reset', now()->addMinute(40), ['email' => $fields['email']]);
    
    
            $url = str_replace(env('APP_URL'), env('FRONTEND_URL'), $url);
            // dd($url);
            Mail::to($fields['email'])->send(new ResetPasswordLink($url));
    
            return response([
                'message' => 'Reset password link sent on your email'
            ]);
        }
    
        public function reset(Request $request)
        {
    
            $fields = $request->validate([
                'email' => ['required', 'email', Rule::exists(User::class, 'email')],
                'password' => 'required|min:6|confirmed'
            ]);
    
    
            $user = User::whereEmail($fields['email'])->first();
    
            if (!$user) {
                return response([
                    'message' => 'User not found!'
                ], 404);
            }
    
            $user->password= bcrypt($fields['password']);
            $user->save();
            return response([
              'message'=> 'Password reset successfully'
            ], 200);
        }
    
}
