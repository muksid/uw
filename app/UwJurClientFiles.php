<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurClientFiles extends Model
{
    //
    protected $fillable = [
        'jur_clients_id',
        'file_path',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];
}
