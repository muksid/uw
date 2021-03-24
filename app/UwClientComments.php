<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientComments extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'claim_id',
        'title',
        'comment_type'
    ];
}
