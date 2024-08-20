<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverViolation extends Model
{
    use HasFactory;

    public $relation = ['drivervehicle', 'violations'];

    protected $table = "driver_violation";

    protected $fillable = [
        'vehicleID',
        'driverID',
        'officerID',
        'violation',
        'fee',
        'status'
    ];

    public function drivervehicle() {
        return $this->hasOne(DriverVehicle::class, 'id', 'vehicleID');
    }

    public function violations() {
        return $this->hasOne(Violations::class, 'id', 'violation');
    }
}
