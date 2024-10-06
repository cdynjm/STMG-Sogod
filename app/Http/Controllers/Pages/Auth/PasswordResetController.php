<?php

namespace App\Http\Controllers\Pages\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PasswordResetController extends Controller
{
    public function request() {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function email(Request $request) {
       
        $request->validate(['email' => 'required|email']);
    
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'A reset link has been sent to your email address.');
        }
    
        return back()->withErrors(['email' => 'We could not find an account associated with this email address']);
    }
    
    public function reset($token, Request $request){

        return Inertia::render('Auth/ResetPassword', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function update(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
    
        // Attempt to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update the user's password
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
    
                // Fire the password reset event
                event(new PasswordReset($user));
    
                // Log the user in after resetting the password
                Auth::login($user);
            }
        );
    
        // If the password was successfully reset, redirect to the intended page or dashboard
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('dashboard')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}


?>