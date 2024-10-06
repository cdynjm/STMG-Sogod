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

class ExistingViolatorController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function existingViolator(Request $request) {

        $data = DriverInfo::where('id', $this->aes->decrypt($request->id))->first();
        $driver = $data->toArray();
        $driver['id'] = $this->aes->encrypt($data->id);
        
        $violation = Violations::get()

        ->map(function ($data) {
            $array = $data->toArray();
            $array['id'] = $this->aes->encrypt($data->id);
            return $array;
        });

        return Inertia::render('User/Existing-Violator', [
            'violations' => $violation,
            'auth' =>  Auth::user()->load(Auth::user()->relation),
            'driver' => $driver
        ]);
    }

    public function createNewViolation(Request $request) {

        $total = 0;

        DriverInfo::where('id', $this->aes->decrypt($request->id))->update([
            'name' => strtoupper($request->name),
            'age' => $request->age,
            'address' => strtoupper($request->address),
            'contactNumber' => $request->contactNumber,
            'timesTicketed' => $request->timesTicketed + 1
        ]);

        $vehicle = DriverVehicle::create([
            'driverID' => $this->aes->decrypt($request->id),
            'IDtype' => strtoupper($request->IDtype),
            'IDnumber' => strtoupper($request->IDnumber),
            'vehicle' => strtoupper($request->vehicle),
            'plateNumber' => strtoupper($request->plateNumber),
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
                'driverID' => $this->aes->decrypt($request->id),
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
