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
use App\Models\ReceiptInfo;

class ReceiptInfoController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function receiptInfo() {

        $receipt = ReceiptInfo::first();

        return Inertia::render('Admin/ReceiptInfo', [
            'receipt' => $receipt,
            'auth' => Auth::user()->load(Auth::user()->relation)
        ]);
    }

    public function updateReceiptInfo(Request $request) {
        ReceiptInfo::where('id', 1)->update([
            'treasurer' => strtoupper($request->treasurer),
            'collector' => strtoupper($request->collector)
        ]);
    }
}
