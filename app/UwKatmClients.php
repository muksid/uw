<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwKatmClients extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'claim_id',
        'summa',
        'scoring_ball',
        'status',
        'katm_score',
        'katm_tb',
        'json_data',
        'isVersion'
    ];
}
