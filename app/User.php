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
        'old_user_id',
        'status',
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

    public function substrUserName($id)
    {
        //
        $user = User::find($id);

        $substr = mb_substr($user->fname ?? '', 0,1).'.'.mb_substr($user->sname ?? '', 0,1).'.'.$user->lname;

        return $substr;

    }

    public function edoUsers()
    {
        //
        $edoUser = EdoUsers::where('user_id', Auth::id())->where('status', 1)->first();

        $edoRole = $edoUser->role->role_code??'';

        if ($edoRole) {

            return $edoRole;

        } else {

            return false;
        }
    }

    # Sidebar Department Inbox Count
    public function countDepInbox(){

        return $this->hasOne(EdoMessageSubUsers::class, 'to_user_id', 'id')->where('status', 0)->count();

    }

    # Sidebar Department Process Count
    public function countDepProcess(){

        return $this->hasOne(EdoMessageSubUsers::class, 'to_user_id', 'id')->whereIn('status', [1,2])->count();

    }

    public function countDepClosed(){

        return $this->hasMany(EdoMessageSubUsers::class, 'to_user_id', 'id')->where('status',3)->count();

    }

    public function replyMessageDirector(){

        return $this->hasOne(EdoReplyMessage::class, 'director_id', 'id')->first();

    }

    public function protocolMember()
    {
        return $this->hasOne(EdoManagementMembers::class, 'user_id', 'id');
    }

    public function hasManyProtocols()
    {
        $department = Auth::user()->department->depart_id;

        $count_unsigned_protocols = EdoManagementProtocols::where('depart_id', $department)->where('status', 1)->count();
        return $count_unsigned_protocols;
    }

    public function countHRProtocols()
    {
        $user_id = Auth::id();

        if(Auth::user()->edoUsers() == 'helper')    $user_id = Auth::user()->edoHelperParent->user_id;

        $user_count = EdoManagementProtocolMembers::whereHas('protocol', function($query){
                $query->where('depart_id', 11);
            })
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->count();

        return $user_count;
    }
    public function countApparatProtocols()
    {

        $user_id = Auth::id();

        if(Auth::user()->edoUsers() == 'helper')    $user_id = Auth::user()->edoHelperParent->user_id;


        $user_count = EdoManagementProtocolMembers::whereHas('protocol', function($query){
                $query->where('depart_id', 3);
            })
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->count();

        return $user_count;
    }
    public function countStrategyProtocols()
    {

        $user_id = Auth::id();

        if(Auth::user()->edoUsers() == 'helper')    $user_id = Auth::user()->edoHelperParent->user_id;

        $user_count = EdoManagementProtocolMembers::whereHas('protocol', function($query){
                $query->where('depart_id', 24);
            })
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->count();

        return $user_count;
    }

    public function countKaznaProtocols()
    {

        $user_id = Auth::id();

        if(Auth::user()->edoUsers() == 'helper')    $user_id = Auth::user()->edoHelperParent->user_id;

        $user_count = EdoManagementProtocolMembers::whereHas('protocol', function($query){
                $query->where('depart_id', 20);
            })
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->count();

        return $user_count;
    }

    public function edoHelperParent(){

        return $this->hasOne(EdoUsers::class, 'user_child', 'id');
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
