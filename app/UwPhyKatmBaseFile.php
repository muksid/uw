<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwPhyKatmBaseFile extends Model
{
    //
    protected $fillable = [
        'uw_katm_id',
        'uw_clients_id',
        'katm_score',
        'katm_tb',
        'base_file'
    ];
}
