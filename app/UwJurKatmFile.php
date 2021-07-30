<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurKatmFile extends Model
{
    //
    protected $fillable = [
        'jur_clients_id',
        'jur_katm_id',
        'file_hash',
        'file_name',
        'file_type'
    ];
}
