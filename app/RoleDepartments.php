<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleDepartments extends Model
{
    //
    protected $fillable = [
        'filial_code',
        'parent_code',
        'code',
        'lev',
        'order_by',
        'isActive'
    ];

    public function getName() {

        return $this->belongsTo(SDepartments::class,'code','code');

    }

    public function childs() {

        return $this->hasMany(RoleDepartments::class, 'parent_code', 'code');

    }

    public function children() {
        return $this->hasMany(static::class, 'parent_code', 'code')
            ->where('parent_code','!=', null)
            ->where('isActive', '=', 'A')->orderBy('order_by');
    }
}
