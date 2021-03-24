<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnRegions extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'order_r',
        'status'
    ];
}
