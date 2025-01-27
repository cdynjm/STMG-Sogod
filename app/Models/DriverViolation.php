<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverViolation extends Model
{
    use HasFactory, SoftDeletes;

    public $relation = ['drivervehicle', 'violations'];

    protected $table = "driver_violation";

    protected $fillable = [
        'vehicleID',
        'driverID',
        'officerID',
        'violation',
        'fee',
        'status',
        'offense'
    ];

    public function drivervehicle() {
        return $this->hasOne(DriverVehicle::class, 'id', 'vehicleID')->withTrashed();
    }

    public function violations() {
        return $this->hasOne(Violations::class, 'id', 'violation')->withTrashed();
    }
}
