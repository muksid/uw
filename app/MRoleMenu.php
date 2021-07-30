<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MRoleMenu extends Model
{
    //
    protected $fillable = [
        'role_id',
        'menu_id',
        'menu_type',
        'sort',
        'isActive',
        'parent_id'
    ];

    public function userRole()
    {
        # code...
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function menuRole()
    {
        # code...
        return $this->belongsTo(MMenuRoles::class, 'menu_id');
    }

    
    public function parentMenuRole()
    {
        # code...
        return $this->belongsTo(MMenuRoles::class, 'parent_id');
    }


    public function parent()
    {
        # code...
        return $this->belongsTo(MRoleMenu::class, 'parent_id');
    }

    public function getCatigories()
    {
        # code...
    }
}
