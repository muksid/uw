<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MWorkUsers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'emp_id',
        'branch_code',
        'local_code',
        'parent_code',
        'depart_code',
        'tab_num',
        'gen_dep_id',
        'depart_id',
        'job_title',
        'date_begin',
        'date_end',
        'sort',
        'isActive'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

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
        return $this->belongsTo(Filials::class,'branch_code','filial_code');
    }

    public function department()
    {
        return $this->belongsTo(SDepartments::class,'depart_code','code');
    }

    public function parent()
    {
        return $this->belongsTo(SDepartments::class,'parent_code','code');
    }
}
