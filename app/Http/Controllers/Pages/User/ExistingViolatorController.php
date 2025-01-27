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

use App\Models\Region;
use App\Models\Province;
use App\Models\Municipal;
use App\Models\Barangay;

class ExistingViolatorController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function getRegions()
    {
        return Region::all();
    }

    public function getProvinces(Request $request)
    {
        return Province::where('regCode', $request->regCode)->get();
    }

    public function getMunicipals(Request $request)
    {
        return Municipal::where('provCode', $request->provCode)->get();
    }

    public function getBarangays(Request $request)
    {
        return Barangay::where('citymunCode', $request->citymunCode)->get();
    }

    public function existingViolator(Request $request) {

        $data = DriverInfo::where('id', $this->aes->decrypt($request->id))->first();
        $driver = $data->toArray();
        $driver['id'] = $this->aes->encrypt($data->id);
        
        $driverViolation = DriverViolation::where('driverID', $this->aes->decrypt($request->id))->get();

        $violation = Violations::get();

        return Inertia::render('User/Existing-Violator', [
            'violations' => $violation,
            'auth' =>  Auth::user()->load(Auth::user()->relation),
            'driver' => $driver,
            'driverViolation' => $driverViolation
        ]);
    }

    public function createNewViolation(Request $request) {

        $total = 0;

        DriverInfo::where('id', $this->aes->decrypt($request->id))->update([
            'firstname' => strtoupper($request->firstname),
            'middlename' => strtoupper($request->middlename) ?? null,
            'lastname' => strtoupper($request->lastname),
            'suffix' => strtoupper($request->suffix) ?? null,
            'region' => $request->region,
            'province' => $request->province,
            'municipal' => $request->municipal,
            'barangay' => $request->barangay,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender
        ]);

        $vehicle = DriverVehicle::create([
            'driverID' => $this->aes->decrypt($request->id),
            'IDnumber' => strtoupper($request->IDnumber),
            'vehicle' => strtoupper($request->vehicle),
            'plateNumber' => strtoupper($request->plateNumber),
            'location' => strtoupper($request->location),
            'ticketedBy' => 
                Auth::user()->staff->firstname . ' ' . 
                (Auth::user()->staff->middlename != null ? Auth::user()->staff->middlename : '') . ' ' . 
                Auth::user()->staff->lastname . ' ' . 
                (Auth::user()->staff->suffix != null ? Auth::user()->staff->suffix : ''),
            'officerID' => Auth::user()->staff->id,
            'status' => 1,
            'age' => $request->age
        ]);

        foreach ($request->violations as $key => $value) {
            // Get the count of existing violations for this driver and this specific violation
            $existingOffenseCount = DriverViolation::where('driverID', $this->aes->decrypt($request->id))
                ->where('violation', $value)
                ->count();
        
            // Increment the offense count for the new entry
            $newOffenseCount = $existingOffenseCount + 1;
        
            // Create the new violation record with the correct offense count
            DriverViolation::create([
                'vehicleID' => $vehicle->id,
                'driverID' => $this->aes->decrypt($request->id),
                'officerID' => Auth::user()->staff->id,
                'violation' => $value,
                //'fee' => $request->violationAmounts[$value], // Uncomment if needed
                'status' => 1,
                'offense' => $newOffenseCount // Set the offense count dynamically
            ]);
        }
        
  
          DriverVehicle::where('id', $vehicle->id)->update(['violationFee' => $total]);

        return back()->withErrors(['penalty' => $total]);
    }
}
