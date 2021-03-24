<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwUsers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'role_id',
        'filial_id',
        'status'
    ];

    // parent filial

    public function user() {

        return $this->hasOne(User::class,'id','user_id');
    }

    // parent filial
    public function filial() {

        return $this->hasOne(Filials::class,'id','filial_id');
    }

    // parent filial
    public function bxo() {

        return $this->hasOne(Filials::class,'id','filial_id');
    }

    // parent filial
    public function role() {

        return $this->hasOne(Role::class,'id', 'role_id');
    }

}
