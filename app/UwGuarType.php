<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwGuarType extends Model
{
    //
    protected $fillable = [
        'code',
        'title',
        'title_ru',
        'isActive'
    ];
}
