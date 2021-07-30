<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MMenuRoles extends Model
{
    //
    protected $fillable = [
        'title',
        'url_path',
        'lang_code',
        'icon_code',
        'text_class',
        'count',
        'core_menu_id',
        'isActive'
    ];

    public function coreMenu()
    {
        return $this->belongsTo(MCoreMenu::class, 'core_menu_id');
    }
}
