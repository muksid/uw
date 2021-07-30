<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurBalanceChild extends Model
{
    //
    protected $table = 'uw_jur_balance_childs';

    protected $fillable = [
        'uw_jur_balance_id',
        'row_no',
        'sum_begin_period',
        'sum_end_period'
    ];
}
