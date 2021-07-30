<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurSaldo extends Model
{
    //
    protected $fillable = [
        'jur_clients_id',
        'code_filial',
        'client_name',
        'client_acc',
        'all_credit',
        'credit',
        'debit',
        'k2',
        'all_debit',
        'saldo_month',
        'lead_last_date'
    ];
}
