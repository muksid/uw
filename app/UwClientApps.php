<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientApps extends Model
{
    //
    protected $fillable = [
        'type',
        'title',
        'body',
        'status',
    ];

    // public function currentUser()
    // {
    //     return $this->belongsTo(MWorkUsers::class,'work_user_id','id');
    // }

}
