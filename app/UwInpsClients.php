<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwInpsClients extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'claim_id',
        'ORG_INN',
        'INCOME_SUMMA',
        'NUM',
        'PERIOD',
        'ORGNAME',
        'report_json',
        'status'
    ];
}
