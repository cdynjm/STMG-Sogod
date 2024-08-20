<?php

namespace App\Http\Controllers\Layouts;

use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Security\AESCipher;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Violations;
use App\Models\DriverInfo;
use App\Models\DriverVehicle;
use App\Models\DriverViolation;

class NavbarController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function updateProfile(Request $request) {

        if(Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(Auth::user()->id)
            ]
        ])->fails()) { return back()->withErrors([
            'status' => 500,
            'error' => 'Email is already taken'
        ]); }

        if(Auth::user()->role == 1) {
            Auth::user()->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            if(!empty($request->password)) {
                Auth::user()->update([
                    'password' => Hash::make($request->password),
                ]);   
            }
        }

        if(Auth::user()->role == 2) {

            Auth::user()->staff->update([
                'name' => $request->name
            ]);

            Auth::user()->update([
                'email' => $request->email
            ]);

            if(!empty($request->password)) {
                Auth::user()->update([
                    'password' => Hash::make($request->password),
                ]);   
            }
        }
    }
}
