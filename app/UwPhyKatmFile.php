<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwPhyKatmFile extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'uw_katm_id',
        'file_path',
        'file_hash',
        'file_type'
    ];
}
