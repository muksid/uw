<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhyMyidToken extends Model
{
    //
    protected $table = 'phy_myid_token';

    protected $fillable = [
        'access_token',
        'refresh_token'
    ];
}
