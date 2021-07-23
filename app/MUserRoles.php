<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MUserRoles extends Model
{
    //
    protected $fillable = [
        'user_id',
        'role_id',
        'isActive'
    ];

    public function getRoleName()
    {

        return $this->hasOne(Role::class, 'id', 'role_id');

    }
}
