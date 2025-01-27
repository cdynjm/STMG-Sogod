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
use App\Models\Region;
use App\Models\Province;
use App\Models\Municipal;
use App\Models\Barangay;

class StaffController extends Controller
{
    public function __construct(protected AESCipher $aes) {}
    
    public function getRegions()
    {
        return Region::all();
    }

    public function getProvinces(Request $request)
    {
        return Province::where('regCode', $request->regCode)->get();
    }

    public function getMunicipals(Request $request)
    {
        return Municipal::where('provCode', $request->provCode)->get();
    }

    public function getBarangays(Request $request)
    {
        return Barangay::where('citymunCode', $request->citymunCode)->get();
    }

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
            'firstname' => $request->firstname,
            'middlename' => $request->middlename ?? null,
            'lastname' => $request->lastname,
            'suffix' => $request->suffix ?? null,
            'position' => $request->position,
            'region' => $request->region,
            'province' => $request->province,
            'municipal' => $request->municipal,
            'barangay' => $request->barangay,
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
            'firstname' => $request->firstname,
            'middlename' => $request->middlename ?? null,
            'lastname' => $request->lastname,
            'suffix' => $request->suffix ?? null,
            'position' => $request->position,
            'region' => $request->region,
            'province' => $request->province,
            'municipal' => $request->municipal,
            'barangay' => $request->barangay,
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
