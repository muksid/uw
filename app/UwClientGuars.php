<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientGuars extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'guar_type',
        'title',
        'claim_id',
        'address',
        'guar_owner',
        'guar_sum'
    ];

    public function getGuarName()
    {
        return $this->belongsTo(UwGuarType::class,'guar_type','code');
    }

}
