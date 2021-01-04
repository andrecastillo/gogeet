<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    //
    protected $fillable = [
        'name',
        'user_id',
        'description',
        'due_date'
    ];
}
