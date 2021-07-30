<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurClientPersonal extends Model
{
    //
    protected $table = 'uw_jur_client_personal';

    protected $fillable = [
        'jur_clients_id',
        'document_type',
        'document_serial',
        'document_number',
        'document_date',
        'gender',
        'client_type',
        'birth_date',
        'document_region',
        'document_district',
        'resident',
        'family_name',
        'name',
        'patronymic',
        'registration_region',
        'registration_district',
        'registration_address',
        'phone',
        'pin',
        'live_address'
    ];

    public function region()
    {
        return $this->belongsTo(UnRegions::class,'registration_region', 'code');
    }

    public function district()
    {
        return $this->belongsTo(UnDistricts::class,'registration_district', 'code');
    }
}
