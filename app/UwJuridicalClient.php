<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJuridicalClient extends Model
{
    //
    protected $fillable = [
        'claim_id',
        'claim_date',
        'inn',
        'claim_number',
        'agreement_number',
        'agreement_date',
        'resident',
        'juridical_status',
        'nibbd',
        'client_type',
        'jur_name',
        'live_cadastr',
        'owner_form',
        'goverment',
        'registration_region',
        'registration_district',
        'registration_address',
        'phone',
        'hbranch',
        'oked',
        'katm_sir',
        'okpo',
        'code_juridical_person',
        'summa',
        'client_code',
        'loan_type_id',
        'branch_code',
        'local_code',
        'work_user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(MWorkUsers::class,'work_user_id')->where('isActive', 'A');
    }

    public function filial()
    {
        return $this->belongsTo(Filials::class,'branch_code', 'filial_code');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'local_code', 'local_code');
    }

    public function loanType()
    {
        return $this->belongsTo(UwLoanTypes::class,'loan_type_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(UnRegions::class,'registration_region', 'code');
    }

    public function district()
    {
        return $this->belongsTo(UnDistricts::class,'registration_district', 'code');
    }

    public function uwStatus()
    {
        return $this->belongsTo(UwStatusName::class,'status', 'code')
            ->where('type', 'jur')
            ->where('user_type', 'uw');
    }

}
