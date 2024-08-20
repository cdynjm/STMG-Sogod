<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violations extends Model
{
    use HasFactory;

    protected $table ="violations";

    protected $fillable = [
        'violation',
        'fee'
    ];
}
