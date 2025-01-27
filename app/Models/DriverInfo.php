<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverInfo extends Model
{
    use HasFactory, SoftDeletes;

    public $relation = ['region', 'province', 'municipal', 'barangay'];

    protected $table = "driver_info";

    protected $fillable = [
        'name',
        'birthdate',
        'address',
        'contactNumber',
        'timesTicketed',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'region',
        'province',
        'municipal',
        'barangay',
        'gender'
    ];

    public function region() {
        return $this->hasOne(Region::class, 'regCode', 'region')->withTrashed();
    }
    public function province() {
        return $this->hasOne(Province::class, 'provCode', 'province')->withTrashed();
    }
    public function municipal() {
        return $this->hasOne(Municipal::class, 'citymunCode', 'municipal')->withTrashed();
    }
    public function barangay() {
        return $this->hasOne(Barangay::class, 'brgyCode', 'barangay')->withTrashed();
    }
}
