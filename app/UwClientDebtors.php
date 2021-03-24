<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientDebtors extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'inn',
        'resident',
        'document_serial',
        'document_number',
        'document_date',
        'gender',
        'birth_date',
        'document_region',
        'document_district',
        'family_name',
        'name',
        'patronymic',
        'pin',
        'live_address',
        'job_address',
        'total_sum',
        'total_month',
        'isActive'
    ];
}
