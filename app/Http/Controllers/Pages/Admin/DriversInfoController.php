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

class DriversInfoController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function driversInfo(Request $request) {

        $driverInfo = DriverInfo::where('id', $this->aes->decrypt($request->id))->first();
        $driverVehicle = DriverVehicle::where('driverID', $this->aes->decrypt($request->id))->orderBy('created_at', 'DESC')->get();
        $driverViolation = DriverViolation::with((new DriverViolation)->relation)->where('driverID', $this->aes->decrypt($request->id))->get();

        return Inertia::render('Admin/DriversInfo', [
            'driverInfo' => $driverInfo,
            'driverVehicle' => $driverVehicle,
            'driverViolation' => $driverViolation,
            'auth' => Auth::user()->load(Auth::user()->relation)
        ]);
    }
}
