<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    //
    protected $fillable = [
        'name',
        'user_id',
        'description'
    ];
}
