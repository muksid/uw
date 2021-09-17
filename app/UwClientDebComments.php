<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwClientDebComments extends Model
{
    //
    protected $table = 'uw_client_deb_comments';

    protected $fillable = [
        'uw_deb_id',
        'work_user_id',
        'claim_id',
        'code',
        'title',
        'comment_type',
        'katm_sir',
        'katm_type',
        'katm_descr',
        'process_type',
        'json_data',
        'isVersion'
    ];
}
