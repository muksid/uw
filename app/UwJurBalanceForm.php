<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurBalanceForm extends Model
{
    //
    protected $fillable = [
        'uw_jur_clients_id',
        'year',
        'quarter',
        'ns10_code',
        'ns11_code',
        'tin',
        'company_name',
        'isActive'
    ];
}
