<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

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
        return $this->hasOne(User::class, 'userID', 'id');
    }
}
