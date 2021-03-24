<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27.12.2019
 * Time: 10:42
 */

namespace App;

use Illuminate\Database\Eloquent\Model;


class Department extends Model
{
    //
    protected $fillable = [
        'depart_id',
        'title',
        'title_ru',
        'branch_code',
        'parent_id',
        'status',
    ];

    public function childs() {

        return $this->hasMany('App\Department','parent_id','id')->where('status', 1);

    }


}

