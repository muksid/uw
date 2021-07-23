<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MPersonalUsers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'f_name',
        'l_name',
        'm_name',
        'birthday',
        'email',
        'address'
    ];
}
