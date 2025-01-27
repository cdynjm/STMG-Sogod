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

class TicketHistoryController extends Controller
{
    public function __construct(protected AESCipher $aes) {}

    public function ticketHistory() {

        $ticket = DriverVehicle::with(['driverinfo.region', 'driverinfo.province', 'driverinfo.municipal', 'driverinfo.barangay'])
        ->where('officerID', Auth::user()->staff->id)
        ->where('created_at', 'like', '%'.date('Y').'%')
        ->orderBy('created_at', 'DESC')
        ->get()
        ->map(function ($data) {
            $array = $data->toArray();
            $array['id'] = $this->aes->encrypt($data->id);
            return $array;
        });
    

        $violation = DriverViolation::with((new DriverViolation)->relation)
            ->where('officerID', Auth::user()->staff->id)
            ->where('created_at', 'like', '%'.date('Y').'%')
            ->get()
            ->map(function ($data) {
                $array = $data->toArray();
                $array['vehicleID'] = $this->aes->encrypt($data->vehicleID);
                return $array;
            });

        return Inertia::render('User/Ticket-History', [
            'ticket' => $ticket,
            'violation' => $violation,
            'year' => date('Y'),
            'auth' => Auth::user()->load(Auth::user()->relation)
        ]);
    }

    public function searchYear(Request $request) {

        $ticket = DriverVehicle::with(['driverinfo.region', 'driverinfo.province', 'driverinfo.municipal', 'driverinfo.barangay'])
            ->where('officerID', Auth::user()->staff->id)
            ->where('created_at', 'like', '%'.$request->search.'%')
            ->orderBy('created_at', 'DESC')
            ->get();

        $violation = DriverViolation::with((new DriverViolation)->relation)
            ->where('officerID', Auth::user()->staff->id)
            ->where('created_at', 'like', '%'.$request->search.'%')
            ->get();

        return Inertia::render('User/Ticket-History', [
            'ticket' => $ticket,
            'violation' => $violation,
            'year' => $request->search,
            'auth' => Auth::user()->load(Auth::user()->relation)
        ]);
    }
}
