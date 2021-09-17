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
        'local_code',
        'parent_id',
        'ora_parent_code',
        'ora_code',
        'ora_condition',
        'order_by',
        'status',
    ];

    public function filial() {

        return $this->belongsTo(Department::class,'depart_id','id');

    }

    public function childs() {

        return $this->hasMany(Department::class,'parent_id','id');

    }

}

