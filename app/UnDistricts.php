<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnDistricts extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'region_code',
        'status'
    ];
}
