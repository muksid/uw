<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientsAppLists extends Model
{
    //
    protected $fillable = [
        'uw_client_id',
        'guar_type_id',
        'template_id',
        'status'
    ];

    public function uwPhyClients()
    {
        return $this->belongsTo(UwClients::class,'uw_client_id','id');
    }

    public function guarTypes()
    {
        return $this->belongsTo(UwGuarType::class,'guar_type_id','id');
    }

    public function appTemplatePhy()
    {
        return $this->belongsTo(UwClientApps::class,'template_id','id');
    }
}
