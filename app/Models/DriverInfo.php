<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverInfo extends Model
{
    use HasFactory;

    protected $table = "driver_info";

    protected $fillable = [
        'name',
        'age',
        'address',
        'contactNumber',
        'timesTicketed'
    ];
}
