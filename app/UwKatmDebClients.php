<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwKatmDebClients extends Model
{
    protected $fillable = [
        'uw_deb_id',
        'claim_id',
        'summa',
        'scoring_ball',
        'status',
        'isVersion'
    ];
}
