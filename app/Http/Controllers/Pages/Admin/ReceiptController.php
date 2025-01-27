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

class ReceiptController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function receipt(Request $request) {

        $receipt = DriverVehicle::with((new DriverVehicle)->relation)->where('id', $this->aes->decrypt($request->id))->first();

        return Inertia::render('Admin/Receipt', ['receipt' => $receipt]);
    }
}
