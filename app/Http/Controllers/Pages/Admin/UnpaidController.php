<?php

namespace App\Http\Controllers\Pages\Admin;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Security\AESCipher;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Violations;
use App\Models\DriverInfo;
use App\Models\DriverVehicle;
use App\Models\DriverViolation;
use App\Models\ReceiptInfo;

class UnpaidController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function unpaid() {

        $unpaid = DriverVehicle::with(['driverinfo.region', 'driverinfo.province', 'driverinfo.municipal', 'driverinfo.barangay'])
        ->where('status', 1)
        ->orderBy('created_at', 'DESC')
        ->get()
        ->map(function ($data) {
            $array = $data->toArray();
            $array['id'] = $this->aes->encrypt($data->id);
            return $array;
        });

        $violation = DriverViolation::with((new DriverViolation)->relation)->where('status', 1)->get()
        ->map(function ($data) {
            $array = $data->toArray();
            $array['vehicleID'] = $this->aes->encrypt($data->vehicleID);
            return $array;
        });

        return Inertia::render('Admin/Unpaid', [
            'unpaid' => $unpaid,
            'violation' => $violation,
            'auth' => Auth::user()->load(Auth::user()->relation)
        ]);
    }

    public function paidPenalty(Request $request) {

     $receipt = ReceiptInfo::first();

        DriverVehicle::where('id', $this->aes->decrypt($request->id))->update([
            'status' => 0,
            'treasurer' => $receipt->treasurer,
            'collector' => $receipt->collector,
            'ORnumber' => $request->ORnumber,
            'datePaid' => Carbon::now()
        ]);
        DriverViolation::where('vehicleID', $this->aes->decrypt($request->id))->update(['status' => 0]);
    }
}
