<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyidDistricts extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'region_code',
        'un_code',
        'un_region_code'
    ];
}
