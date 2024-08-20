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

class DashboardController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function dashboard() {

        $unpaid = DriverVehicle::where('status', 1)->count();

        $vehicle = DriverVehicle::with((new DriverVehicle)->relation)
            ->where('created_at', 'like', '%'.date('Y').'%')->get();

        $violation = DriverViolation::with((new DriverViolation)->relation)
            ->where('created_at', 'like', '%'.date('Y').'%')->get();

        $violationList = Violations::get();

        return Inertia::render('Admin/Dashboard', [
            'unpaid' => $unpaid,
            'vehicleCount' => $vehicle->count(),
            'vehicle' => $vehicle,
            'violation' => $violation,
            'violationList' => $violationList,
            'auth' => Auth::user()->load(Auth::user()->relation),
            'year' => date('Y')
        ]);
    }

    public function searchYearAnalytics(Request $request) {

        $unpaid = DriverVehicle::where('status', 1)->count();

        $vehicle = DriverVehicle::with((new DriverVehicle)->relation)
            ->where('created_at', 'like', '%'.$request->search.'%')->get();

        $violation = DriverViolation::with((new DriverViolation)->relation)
            ->where('created_at', 'like', '%'.$request->search.'%')->get();

        $violationList = Violations::get();

        return Inertia::render('Admin/Dashboard', [
            'unpaid' => $unpaid,
            'vehicleCount' => $vehicle->count(),
            'vehicle' => $vehicle,
            'violation' => $violation,
            'violationList' => $violationList,
            'auth' => Auth::user()->load(Auth::user()->relation),
            'year' => $request->search
        ]);
    }
}
