<?php

namespace App\Http\Controllers\Pages\Admin;

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
use App\Models\Staff;

class StaffController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function staff() {
        
        $staff = Staff::with((new Staff)->relation)->get()
        
        ->map(function ($data) {
            $array = $data->toArray();
            $array['id'] = $this->aes->encrypt($data->id);
            return $array;
        });

        return Inertia::render('Admin/Staff', [
            'staff' => $staff,
            'auth' => Auth::user()->load(Auth::user()->relation)
        ]);
    }

    public function createStaff(Request $request) {
        
        if(Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
            ]
        ])->fails()) { return back()->withErrors([
            'status' => 500,
            'error' => 'Email is already taken'
        ]); }

        $staff = Staff::create([
            'name' => $request->name,
            'position' => $request->position,
            'address' => $request->address,
            'contactNumber' => $request->contactNumber
        ]);

        User::create([
            'userID' => $staff->id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 2
        ]);

    }

    public function updateStaff(Request $request) {
        
        if(Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->aes->decrypt($request->id), 'userID')
            ]
        ])->fails()) { return back()->withErrors([
            'status' => 500,
            'error' => 'Email is already taken'
        ]); }

        $staff = Staff::where('id', $this->aes->decrypt($request->id))->update([
            'name' => $request->name,
            'position' => $request->position,
            'address' => $request->address,
            'contactNumber' => $request->contactNumber
        ]);

        User::where('userID', $this->aes->decrypt($request->id))->update(['email' => $request->email]);

        if(!empty($request->password)) {
            User::where('userID', $this->aes->decrypt($request->id))->update(['password' => Hash::make($request->password)]);
        }
    }

    public function deleteStaff(Request $request) {
        Staff::where('id', $this->aes->decrypt($request->id))->delete();
        User::where('userID', $this->aes->decrypt($request->id))->delete();
    }
}
