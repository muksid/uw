<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use App\MRoleMenus;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'cb_id',
        'tab_num',
        'old_user_id',
        'status',
        'isActive',
        'isUw',
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

    public function personal()
    {
        return $this->belongsTo(MPersonalUsers::class,'id', 'user_id');
    }

    public function currentWork()
    {
        return $this->belongsTo(MWorkUsers::class,'id','user_id')->where('isActive', 'A');
    }

    public function filial()
    {
        return $this->belongsTo(Department::class,'branch_code','branch_code')->where('parent_id', 0);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'depart_id');
    }

    public function substrUserName($id)
    {
        //
        $user = User::find($id);

        $substr = mb_substr($user->fname ?? '', 0,1).'.'.mb_substr($user->sname ?? '', 0,1).'.'.$user->lname;

        return $substr;

    }

    public function branch()
    {
        return $this->belongsTo(MWorkUsers::class,'branch_code');
    }

    public function getMenus()
    {
        $m_work_user = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();

        $roles = MUserRoles::where('user_id', $m_work_user->id)->where('isActive','A')->get();

        $arr_roles = array();
        foreach ($roles as $key => $value) {
            array_push($arr_roles,$value->role_id);
        }

        $r = MRoleMenu::whereIn('role_id', $arr_roles)->orderBy('menu_type', 'ASC')->orderBy('sort', 'ASC')->get();

        return $r;
    }

    public function getCategory()
    {
        $m_work_user = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();
        $roles = MUserRoles::where('user_id', $m_work_user->id)->where('isActive','A')->get();

        $arr_roles = array();
        foreach ($roles as $key => $value) {
            array_push($arr_roles,$value->role_id);
        }

        $r = MRoleMenu::whereIn('role_id', $arr_roles)
            ->where('parent_id', 0)
            ->orderBy('sort', 'ASC')
            ->get();

        return $r;
    }


}
