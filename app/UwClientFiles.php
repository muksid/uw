<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientFiles extends Model
{
    //
    protected $fillable = [
        'uw_client_id',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];
}
