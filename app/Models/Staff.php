<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    public $relation = ['user', 'region', 'province', 'municipal', 'barangay'];

    protected $table ="staff";

    protected $fillable = [
        'id',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'position',
        'address',
        'region',
        'province',
        'municipal',
        'barangay',
        'contactNumber'
    ];

    public function user() {
        return $this->hasOne(User::class, 'userID', 'id')->withTrashed();
    }
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
