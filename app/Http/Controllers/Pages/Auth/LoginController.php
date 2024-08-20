<?php

namespace App\Http\Controllers\Pages\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function login() {
        return Inertia::render('Auth/Login');
    }

    public function authenticate(LoginRequest $request) {
        try {

            $request->authenticate();

            $request->session()->regenerate();

            return redirect('/');
            
        } catch (ValidationException $e) {
            
            return back()->withErrors(['status' => $e->getMessage()]);
        }
    }


    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
