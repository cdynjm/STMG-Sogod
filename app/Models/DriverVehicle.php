<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverVehicle extends Model
{
    use HasFactory;

    public $relation = ['driverinfo'];

    protected $table = "driver_vehicle";

    protected $fillable = [
        'driverID',
        'IDtype',
        'IDnumber',
        'vehicle',
        'ViolationFee',
        'location',
        'ticketedBy',
        'officerID',
        'status'
    ];

    public function driverinfo() {
        return $this->hasOne(DriverInfo::class, 'id', 'driverID');
    }
}
