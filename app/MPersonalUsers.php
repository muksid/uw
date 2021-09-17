<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MPersonalUsers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'emp_id',
        'f_name',
        'l_name',
        'm_name',
        'birthday',
        'pinfl',
        'inn',
        'doc_series',
        'doc_number',
        'doc_begin_date',
        'doc_end_date',
        'doc_address',
        'mobile_phone',
        'login_phone',
        'image_path',
        'email',
        'address'
    ];

    public function getReplace($str)
    {

        $res1 = str_replace('&#1170;', 'Ғ', $str);

        $res2 = str_replace('&#1171;', 'ғ', $res1);

        $res3 = str_replace('"&#1178;', 'Қ', $res2);

        $res4 = str_replace('&#1179;', 'қ', $res3);

        $res5 = str_replace('"&#1202;', 'Ҳ', $res4);

        $res6 = str_replace('&#1203;', 'ҳ', $res5);

        return $res6;
    }

}
