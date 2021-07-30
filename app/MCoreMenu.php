<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MCoreMenu extends Model
{
    //
    protected $fillable = [
        'title',
        'url_path',
        'lang_code',
        'icon_code',
        'text_class',
        'isActive'
    ];
}
