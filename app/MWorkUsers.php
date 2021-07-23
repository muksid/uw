<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MWorkUsers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'branch_code',
        'tab_num',
        'gen_dep_id',
        'depart_id',
        'job_title',
        'date_begin',
        'date_end',
        'ip_phone',
        'mob_phone',
        'sort',
        'isActive'
    ];

    public function personal()
    {
        return $this->belongsTo(MPersonalUsers::class,'user_id','user_id');
    }

    public function roleId()
    {
        return $this->hasMany(MUserRoles::class,'user_id','id');
    }

    public function filial()
    {
        return $this->belongsTo(Department::class,'branch_code','branch_code')->where('parent_id', 0);
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'depart_id');
    }
}
