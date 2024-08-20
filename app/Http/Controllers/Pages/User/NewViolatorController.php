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

class NewViolatorController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function newViolator() {

        $violation = Violations::get()

        ->map(function ($data) {
            $array = $data->toArray();
            $array['id'] = $this->aes->encrypt($data->id);
            return $array;
        });

        return Inertia::render('User/New-Violator', [
            'violations' => $violation,
            'auth' =>  Auth::user()->load(Auth::user()->relation)
        ]);
    }

    public function createNewViolator(Request $request) {

        $total = 0;

        $driver = DriverInfo::create([
            'name' => strtoupper($request->name),
            'age' => $request->age,
            'address' => strtoupper($request->address),
            'contactNumber' => $request->contactNumber,
            'timesTicketed' => $request->timesTicketed
        ]);

        $vehicle = DriverVehicle::create([
            'driverID' => $driver->id,
            'IDtype' => strtoupper($request->IDtype),
            'IDnumber' => strtoupper($request->IDnumber),
            'vehicle' => strtoupper($request->vehicle),
            'ViolationFee' => $request->violationFee,
            'location' => strtoupper($request->location),
            'ticketedBy' => Auth::user()->staff->name,
            'officerID' => Auth::user()->staff->id,
            'status' => 1
        ]);

        foreach($request->violations as $key => $value) {

            $total += Violations::where('id', $this->aes->decrypt($value))->first()->fee;

            $fee = Violations::where('id', $this->aes->decrypt($value))->first()->fee;

            DriverViolation::create([
                'vehicleID' => $vehicle->id,
                'driverID' => $driver->id,
                'officerID' => Auth::user()->staff->id,
                'violation' => $this->aes->decrypt($value),
                'fee' => $fee,
                'status' => 1
            ]);
        }

        DriverVehicle::where('id', $vehicle->id)->update(['violationFee' => $total]);
        
        return back()->withErrors(['penalty' => $total]);
    }
}
