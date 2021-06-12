<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwLoanBank extends Model
{
    //
    protected $fillable = [
        'loan_types_id',
        'filials_id',
        'startDate',
        'endDate',
        'isActive'
    ];

    public function loan()
    {
        return $this->belongsTo(UwLoanTypes::class,'loan_types_id');
    }
}
