<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwKatmClients extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'claim_id',
        'katm_summ',
        'katm_sc_ball',
        'status',
        'katm_score',
        'katm_tb'
    ];
}
