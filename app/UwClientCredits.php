<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientCredits extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'claim_id',
        'credit_security',
        'credit_security_name'
    ];
}
