<?php

namespace App\Http\Controllers\Pages\Admin;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Security\AESCipher;

use App\Models\User;
use App\Models\Violations;
use App\Models\DriverInfo;
use App\Models\DriverVehicle;
use App\Models\DriverViolation;

class UnpaidController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function unpaid() {

        $unpaid = DriverVehicle::with((new DriverVehicle)->relation)->where('status', 1)->get();
        $violation = DriverViolation::with((new DriverViolation)->relation)->where('status', 1)->get();

        return Inertia::render('Admin/Unpaid', [
            'unpaid' => $unpaid,
            'violation' => $violation,
            'auth' => Auth::user()->load(Auth::user()->relation)
        ]);
    }

    public function paidPenalty(Request $request) {

        DriverVehicle::where('id', $request->id)->update(['status' => 0]);
        DriverViolation::where('vehicleID', $request->id)->update(['status' => 0]);
    }
}
