<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwLoanTypes extends Model
{
    //
    protected $fillable = [
        'title',
        'credit_type',
        'procent',
        'credit_duration',
        'credit_exemtion',
        'currency',
        'short_code',
        'dept_procent',
        'isActive'
    ];

    public function banks()
    {
        return $this->belongsTo(UwLoanBank::class,'id', 'loan_types_id');
    }
}
