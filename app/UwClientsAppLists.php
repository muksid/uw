<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientsAppLists extends Model
{
    //
    protected $fillable = [
        'loan_id'       ,
        'client_code'   ,
        'contract_code' ,
        'contract_date' ,
        'summ_loan'     ,
        'client_name'   ,
        'address'       ,
        'typeof'        ,
        'subject'       ,
        'filial_code'   ,
        'saldo_in_5'    ,
        'saldo_in_all'  ,
        'template_id'   ,
        'status'        ,
    ];

    public function appTemplate()
    {
        return $this->belongsTo(UwClientApps::class,'template_id','id');
    }

    public function filial()
    {
        return $this->hasOne(Filials::class,'filial_code','filial_code');
    }
}
