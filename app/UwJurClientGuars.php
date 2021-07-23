<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurClientGuars extends Model
{
    //
    protected $fillable = [
        'jur_clients_id',
        'guar_type',
        'title',
        'guar_sum'
    ];
}
