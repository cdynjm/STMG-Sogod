<?php

namespace App\Http\Controllers\Pages\User;

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

        return Inertia::render('User/Dashboard', [
            'auth' =>  Auth::user()->load(Auth::user()->relation)
        ]);
    }

    public function searchExistingViolator(Request $request) {

       $drivers = DriverInfo::with((new DriverInfo)->relation)->where('firstname', 'like', '%' . $request->search . '%')
        ->orWhere('middlename', 'like', '%' . $request->search . '%')
        ->orWhere('lastname', 'like', '%' . $request->search . '%')
        ->orWhere('suffix', 'like', '%' . $request->search . '%')
        ->get()

        ->map(function ($data) {
            $array = $data->toArray();
            $array['id'] = $this->aes->encrypt($data->id);
            return $array;
        });

        return Inertia::render('User/Dashboard', [
            'auth' =>  Auth::user()->load(Auth::user()->relation),
            'drivers' => $drivers,
            'result' => $drivers->count()
        ]);
    }
}
