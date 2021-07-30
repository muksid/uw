<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurKatmClient extends Model
{
    //
    protected $fillable = [
        'jur_clients_id',
        'claim_id',
        'summa',
        'scoring_ball',
        'json_data',
        'status'
    ];
}
