<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientsAppLists extends Model
{
    //
    protected $fillable = [
        'uw_client_id',
        'client_type',
        'template_id',
        'status'
    ];

    public function uwPhyClients()
    {
        return $this->belongsTo(UwClients::class,'uw_client_id','id');
    }

    public function appTemplate()
    {
        return $this->belongsTo(UwClientApps::class,'template_id','id');
    }
}
