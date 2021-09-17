<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwKatmDebClients extends Model
{
    //
    protected $table = 'uw_katm_deb_clients';
    protected $fillable = [
        'uw_deb_id',
        'claim_id',
        'summa',
        'scoring_ball',
        'status',
        'isVersion'
    ];
}
