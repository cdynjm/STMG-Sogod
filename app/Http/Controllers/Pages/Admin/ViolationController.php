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
use App\Models\Violations;

class ViolationController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function violation() {

        $violation = Violations::get()
        
        ->map(function ($data) {
            $array = $data->toArray();
            $array['id'] = $this->aes->encrypt($data->id);
            return $array;
        });

        return Inertia::render('Admin/Violation', [
            'violation' => $violation,
            'auth' => Auth::user()->load(Auth::user()->relation),
        ]);
    }

    public function createViolation(Request $request) {
        Violations::create([
            'violation' => $request->violation,
            'fee' => $request->fee
        ]);
    }

    public function updateViolation(Request $request) {
        Violations::where('id', $this->aes->decrypt($request->id))->update([
            'violation' => $request->violation,
            'fee' => $request->fee
        ]);
    }

    public function deleteViolation(Request $request) {
        Violations::where('id', $this->aes->decrypt($request->id))->delete();
    }
}
