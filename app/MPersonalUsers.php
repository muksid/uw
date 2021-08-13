<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MPersonalUsers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'emp_id',
        'f_name',
        'l_name',
        'm_name',
        'birthday',
        'pinfl',
        'inn',
        'doc_series',
        'doc_number',
        'doc_begin_date',
        'doc_end_date',
        'doc_address',
        'mobile_phone',
        'login_phone',
        'image_path',
        'email',
        'address'
    ];
}
