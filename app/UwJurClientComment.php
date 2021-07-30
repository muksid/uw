<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJurClientComment extends Model
{
    //
    protected $fillable = [
        'jur_clients_id',
        'code',
        'title',
        'json_data',
        'process_type',
        'status',
        'work_user_id'
    ];

    public function currentUser()
    {
        return $this->belongsTo(MWorkUsers::class,'work_user_id','id');
    }
}
