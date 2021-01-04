<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Principle extends Model
{
    //
    protected $fillable = [
        'name',
        'user_id',
        'description'
    ];
}
