<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filials extends Model
{
    //
    protected $fillable = [
        'title',
        'title_ru',
        'filial_code',
        'local_code',
        'parent_id',
        'f_sort',
        'status'
    ];

    // parent filial
    public function filial() {

        return $this->hasOne(Filials::class,'filial_code','filial_code');

    }
}
