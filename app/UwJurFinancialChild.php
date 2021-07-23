<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurFinancialChild extends Model
{
    //
    protected $table = 'uw_jur_financial_childs';

    protected $fillable = [
        'uw_jur_financial_id',
        'row_no',
        'sum_old_period_doxod',
        'sum_old_period_rasxod',
        'sum_period_doxod',
        'sum_period_rasxod'
    ];
}
