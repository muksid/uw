<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwInpsDebClients extends Model
{
    //
    protected $fillable = [
        'uw_deb_id',
        'claim_id',
        'client_name', //client name
        'client_tin', //client name
        'pinfl', //client pin_fl
        'ns10_code', //kod oblasti
        'ns11_code', //kod rayona
        'salary_tax_sum', //12% nalog
        'inps_sum', //inps sum
        'prop_income', //unk
        'other_income', //unk
        'series_passport', //client ser passport
        'number_passport', //client num passport
        'isVersion', //version xb or soliq
        'ORG_INN',
        'INCOME_SUMMA',
        'NUM',
        'PERIOD',
        'ORGNAME',
        'status'
    ];
}
