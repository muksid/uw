<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientDebtors extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'branch_code',
        'claim_id',
        'claim_number',
        'katm_sir',
        'inn',
        'resident',
        'document_type',
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
        'isReg',
        'salary',
        'type',
        'isActive'
    ];

    //Javlon

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function uwUser()
    {
        return $this->belongsTo(UwUsers::class,'user_id', 'user_id')->where('status', 1);
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
        return $this->hasMany(UwClientFiles::class, 'uw_clients_id');
    }

    public function uwClient()
    {
        return $this->hasMany(UwClientFiles::class, 'uw_clients_id');
    }
}
