<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwPhyInpsBaseFile extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'base_file'
    ];
}
