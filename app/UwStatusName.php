<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwStatusName extends Model
{
    //
    protected $table = 'uw_status_names';

    protected $fillable = [
        'name',
        'name_ru',
        'type',
        'user_type',
        'code',
        'status_code',
        'isActive',
        'bg_style',
        'order'
    ];
}
