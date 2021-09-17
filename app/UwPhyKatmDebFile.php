<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwPhyKatmDebFile extends Model
{
    //
    protected $table = 'uw_phy_katm_deb_files';
    protected $fillable = [
        'uw_deb_id',
        'uw_katm_id',
        'file_path',
        'file_hash',
        'file_type'
    ];
}
