<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyidIibDistricts extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'name_ru',
        'region_code',
        'district_code'
    ];
}
