<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    public $relation = ['user'];

    protected $table ="staff";

    protected $fillable = [
        'id',
        'name',
        'position',
        'address',
        'contactNumber'
    ];

    public function user() {
        return $this->hasOne(User::class, 'userID', 'id')->withTrashed();
    }
}
