<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClients extends Model
{
    //
    protected $fillable = [
        'loan_type_id',
        'work_user_id',
        'branch_code',
        'local_code',
        'claim_id',
        'claim_date',
        'inn',
        'claim_number',
        'agreement_number',
        'agreement_date',
        'resident',
        'document_type',
        'document_serial',
        'document_number',
        'document_date',
        'gender',
        'client_type',
        'birth_date',
        'document_region',
        'document_district',
        'family_name',
        'name',
        'patronymic',
        'registration_region',
        'registration_district',
        'registration_address',
        'phone',
        'pin',
        'katm_sir',
        'summa',
        'sch_type',
        'live_address',
        'live_number',
        'job_address',
        'status',
        'reg_status',
        'is_inps',
        'iabs_num',
        'descr'
    ];

    public function currentWork()
    {
        return $this->belongsTo(MWorkUsers::class,'work_user_id','id')->where('isActive', 'A');
    }

    public function department()
    {
        return $this->belongsTo(SDepartments::class,'local_code','local_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function filial()
    {
        return $this->belongsTo(Filials::class,'branch_code', 'filial_code');
    }

    public function region()
    {
        return $this->belongsTo(UnRegions::class,'registration_region', 'code');
    }

    public function district()
    {
        return $this->belongsTo(UnDistricts::class,'registration_district', 'code');
    }

    public function region1()
    {
        return $this->belongsTo(UnRegions::class,'document_region', 'code');
    }

    public function docDistrict()
    {
        return $this->belongsTo(UnDistricts::class,'document_district', 'code');
    }

    public function katm()
    {
        return $this->belongsTo(UwKatmClients::class,'claim_id', 'claim_id');
    }

    public function credits()
    {
        return $this->belongsTo(UwClientCredits::class,'id', 'uw_clients_id');
    }

    public function inps()
    {
        return $this->hasMany(UwInpsClients::class,'claim_id', 'claim_id');
    }

    public function loanType()
    {
        return $this->belongsTo(UwLoanTypes::class,'loan_type_id', 'id');
    }

    public function inps1($id)
    {
        //
        $inps = UwInpsClients::where('uw_clients_id', $id)->get();

        $summ = UwInpsClients::select('INCOME_SUMMA')->where('uw_clients_id', $id)->get();

        $total = 0;
        foreach ($summ as $key => $value){
            $total +=$value->INCOME_SUMMA;
        }

        $inps = $inps->count();
        if (!$inps){
            $inps = 1;
        }

        return $total / $inps;
    }

    public function files()
    {
        return $this->hasMany(UwClientFiles::class, 'uw_client_id');
    }
}
