<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SDepartments extends Model
{
    //
    protected $fillable = [
        'title',
        'title_ru',
        'code',
        'local_code',
        'isActive'
    ];

    public function getReplace($str)
    {

        $res1 = str_replace('&#1170;', 'Ғ', $str);

        $res2 = str_replace('&#1171;', 'ғ', $res1);

        $res3 = str_replace('&#1178;', 'Қ', $res2);

        $res4 = str_replace('&#1179;', 'қ', $res3);

        $res5 = str_replace('"&#1202;', 'Ҳ', $res4);

        $res6 = str_replace('&#1203;', 'ҳ', $res5);

        return $res6;
    }
}
