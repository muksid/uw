<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname',
        'lname',
        'sname',
        'roles',
        'username',
        'card_num',
        'user_gen',
        'branch_code',
        'depart_id',
        'job_title',
        'job_date',
        'user_sort',
        'status',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function filial()
    {
        return $this->belongsTo(Department::class,'branch_code','branch_code')->where('parent_id', 0);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'depart_id');
    }

    public function isAdmin()
    {
        $roles = Role::all();
        foreach ($roles as $role){
            if ($role->role_code == 'admin'){
                return true;
            } else {

                return response()->view('errors.' . '404', [], 404);
            }
        }

    }

    public function substrUserName($id)
    {
        //
        $user = User::find($id);

        $substr = mb_substr($user->fname ?? '', 0,1).'.'.mb_substr($user->sname ?? '', 0,1).'.'.$user->lname;

        return $substr;

    }

    public function uwUsers()
    {
        //
        $user = UwUsers::where('user_id', Auth::id())->where('status', 1)->first();

        $uwRole = $user->role->role_code??'';

        if ($uwRole) {
            return $uwRole;

        } else {

            return false;
        }
    }

}
