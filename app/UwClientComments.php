<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientComments extends Model
{
    //
    protected $fillable = [
        'uw_clients_id',
        'old_user_id',
        'work_user_id',
        'claim_id',
        'code',
        'title',
        'comment_type',
        'katm_sir',
        'katm_type',
        'katm_descr',
        'process_type',
        'json_data',
        'isVersion'
    ];

    public function currentUser()
    {
        return $this->belongsTo(MWorkUsers::class,'work_user_id','id');
    }


}
