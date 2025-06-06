<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverVehicle extends Model
{
    use HasFactory, SoftDeletes;

    public $relation = ['driverinfo'];

    protected $table = "driver_vehicle";

    protected $fillable = [
        'age',
        'driverID',
        'IDtype',
        'IDnumber',
        'vehicle',
        'plateNumber',
        'ViolationFee',
        'location',
        'ticketedBy',
        'officerID',
        'status',
        'treasurer',
        'collector',
        'ORnumber',
        'datePaid'
    ];

    public function driverinfo() {
        return $this->hasOne(DriverInfo::class, 'id', 'driverID')->withTrashed();
    }
}
