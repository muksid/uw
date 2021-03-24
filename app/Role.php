<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{

    protected $fillable = [
        'title',
        'title_ru',
        'role_code'
    ];

    /**

     * Get the index name for the model.

     *

     * @return string

     */



}