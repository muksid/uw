<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhyMyidClient extends Model
{
    //
    protected $fillable = [
        // common_data
        'first_name',
        'middle_name',
        'last_name',
        'pinfl',
        'inn',
        'gender',
        'birth_place',
        'birth_country',
        'birth_date',
        'nationality',
        'citizenship',
        // doc_data
        'pass_data', //ab1112233
        'issued_by', // baxmal iib
        'issued_by_id', // baxmal iib code
        'issued_date', // beril vaq
        'expiry_date', // amal qil vaq
        // contacts
        'phone',
        'email',
        // address
        'permanent_address', // doimiy yashash manzili
        'temporary_address', // vaqtinchalik yashash manzili
        // address JSON
        'permanent_registration', // vaqtinchalik yashash manzili JSON
        'temporary_registration', // vaqtinchalik yashash manzili JSON
        // FILIAL
        'branch_code',
        'work_user_id',
        'img_path',
        'isActive'
    ];
}
