<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyidRegions extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'name_ru',
        'un_code'
    ];
}
