<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "driver_info";

    protected $fillable = [
        'name',
        'age',
        'address',
        'contactNumber',
        'timesTicketed'
    ];
}
