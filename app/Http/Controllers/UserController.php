<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Role;
use App\Department;
use App\MWorkUsers;
use App\MPersonalUsers;
use App\MUserRoles;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $filials = Department::where('parent_id', 0)->where('status', 1)->orderBy('branch_code', 'ASC')->get();

        $roles = Role::all();

        $search = User::orderBy('id', 'DESC');

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $uw = Input::get ( 'uw' );
        $r = Input::get ( 'r' );

        if($u) {
            $search->whereHas('currentWork', function ($query) use($u){
                $query->where('id', $u);
            });
        }

        if($uw) {
            $search->where('isUw', $uw);
        }

        if($r) {
            $work_user_id = MWorkUsers::leftJoin('m_user_roles', function($join) {
                $join->on('m_work_users.id', '=', 'm_user_roles.user_id');
                })
                ->leftJoin('roles', function($join) {
                    $join->on('m_user_roles.role_id', '=', 'roles.id');
                })
                ->where('roles.id', $r)
                ->where('m_work_users.isActive', '=', 'A')
                ->pluck('m_work_users.user_id')->toArray();

            $search->whereIn('id', $work_user_id);

            $r = Role::find($r);
        }

        if($t) {
            $search->where('username', 'LIKE', '%' . $t . '%');
            $search->orWhereHas('personal', function ($query) use($t){
                $query->whereRaw("concat(l_name, ' ', f_name) like '%".$t."%' ");
            });
        }

        $models = $search->paginate(20);

        $models->appends ( array (
            'u' => Input::get ( 'u' ),
            'r' => Input::get ( 'r' ),
            't' => Input::get ( 't' )
        ) );

        return view('madmin.users.index',
            compact('models','filials','roles','t', 'uw', 'r'))
            ->withDetails ( $models )->withQuery ( $u, $t );

    }

    public function create()
    {

        $work_user = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();

        $user_roles = DB::table('m_user_roles as a')
            ->join('roles as r', 'r.id', 'a.role_id')
            ->where('a.user_id', $work_user->id)
            ->where('a.isActive', 'A')
            ->groupBy('a.role_id')
            ->get();

        $user_arr_roles = array();
        if($user_roles){
            foreach ($user_roles as $key => $value) {
                array_push($user_arr_roles, $value->role_code);
            }
        }

        if(in_array( 'madmin', $user_arr_roles))
        {
            $roles = Role::all(); //get all roles

            $filials = Department::where('parent_id', 0)
                ->where('status', 1)
                ->orderBy('id','ASC')
                ->get();

            return view('madmin.users.create',compact('roles', 'filials'));

        }
        elseif(in_array( 'branch_admin', $user_arr_roles))
        {

            $work_user = MWorkUsers::where('user_id', Auth::id())->firstOrFail();

            $filials = Department::where('parent_id', 0)
                ->where('branch_code', $work_user->branch_code)
                ->where('status', 1)
                ->orderBy('id','ASC')
                ->get();

            $roles = Role::where('for_others', 'B')->get(); //get roles for branch admin

            return view('madmin.users.create',compact('roles', 'filials'));
        }
        else
        {
            return response()->view('errors.' . '404', [], 404);
        }
    }

    public function getBranch($id)
    {
        $depart = Department::findOrFail($id);

        $branch = Department::where('parent_id', 0)->where('branch_code',$depart->branch_code)->first();

        return response()->json(['depart' => $depart, 'branch' => $branch], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required|max:25|unique:users',
            'password'  => 'required|confirmed|min:6',
            'status'    => 'required',

            'f_name'    => 'required|max:25',
            'l_name'    => 'required|max:25',
            'm_name'    => 'required|max:25',
            'email'     => 'required|email|max:25|unique:m_personal_users',

            'depart_id' => 'required',
            'job_title' => 'required',
            'tab_num'   => 'required|max:15|unique:m_work_users',
            'date_begin'=> 'required',
            'sort'      => 'required|max:10',
            'isActive'  => 'required',

            'roles'     => 'required',

        ]);

        $password = Hash::make($request->input('password'));
        $login_input = array_merge($request->only('username','status'), ['password' => $password]);
        $user = User::firstOrCreate($login_input);

        $personal_input = array_merge($request->only('f_name','l_name','m_name', 'birthday','email', 'address'), ['user_id' => $user->id]);

        $personal = MPersonalUsers::firstOrCreate($personal_input);
        $branch = Department::find($request->input('depart_id'));
        $work_user_input = array_merge($request->only('tab_num','depart_id', 'job_title','date_begin','date_end','ip_phone','mob_phone', 'sort', 'isActive'),
            ['user_id' => $user->id, 'branch_code' => $branch->branch_code, 'gen_dep_id' => $branch->depart_id ]);

        $work_user = MWorkUsers::firstOrCreate($work_user_input);

        foreach ($request->input('roles') as $key => $value) {
            $role = new MUserRoles();
            $role->user_id = $work_user->id;
            $role->role_id = $value;
            $role->save();
        }

        return back()->with('success', 'Xodim muvaffaqiyatli qo`shildi');

    }

    public function edit($id)
    {
        $work_user = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();

        $user_roles = DB::table('m_user_roles as a')
            ->join('roles as r', 'r.id', 'a.role_id')
            ->where('a.user_id', $work_user->id)
            ->where('a.isActive', 'A')
            ->groupBy('a.role_id')
            ->get();

        $user_arr_roles = array();

        if($user_roles){
            foreach ($user_roles as $key => $value) {
                array_push($user_arr_roles, $value->role_code);
            }
        }

        if(in_array( 'madmin', $user_arr_roles))
        {
            $user           = User::findOrFail($id);
            $personal_user  = MPersonalUsers::where('user_id', $user->id)->first();
            $model          = MWorkUsers::where('user_id', $user->id)->where('isActive', 'A')->first();
            if($model === null) $model = MWorkUsers::where('user_id', $user->id)->orderBy('updated_at', 'DESC')->first();

            $roles = Role::all(); //get all roles

            $filials = Department::where('parent_id', 0)
                ->where('status', 1)
                ->orderBy('id','ASC')
                ->get();

            return view('madmin.users.edit',
                compact('user','personal_user','model', 'filials','roles'));

        } elseif ($user_arr_roles[2] = 'uw_admin '){
            $user           = User::findOrFail($id);
            $personal_user  = MPersonalUsers::where('user_id', $user->id)->first();
            $model          = MWorkUsers::where('user_id', $user->id)->where('isActive', 'A')->first();
            if($model === null) $model = MWorkUsers::where('user_id', $user->id)->orderBy('updated_at', 'DESC')->first();

            $roles = Role::where('role_code', '!=', 'madmin')->get(); //get all roles

            $filials = Department::where('parent_id', 0)
                ->where('status', 1)
                ->orderBy('id','ASC')
                ->get();

            return view('madmin.users.edit',
                compact('user','personal_user','model', 'filials','roles'));
        }
        else
        {
            return view('errors.' . '404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($id == Auth::user()->id){

            $this->validate($request, [
                'password' => 'confirmed'
            ]);

            $input = $request->only('password');

            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']); //update the password
            }else{
                $input = array_except($input,array('password')); //remove password from the input array
            }

            $user->update($input); //update the user info

            return back()->with('success', 'Sizning profilingiz muvaffaqiyatli yangilandi');

        } else{

            $this->validate($request, [
                'password'  => 'confirmed'
            ]);

            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = array_except($input,array('password'));
            }

            $user->update($input);

            return redirect()->route('madmin.users.index')
                ->with('success','Xodim muvaffaqiyatli yangilandi');

        }
    }

    // update user,personal, position
    public function updateUser(Request $request)
    {
        $this->validate($request, [
            'w_user_id' => 'required',
            'user_id'   => 'required',
            'status'    => 'required',
            'f_name'    => 'required|max:25',
            'l_name'    => 'required|max:25',
            'm_name'    => 'required|max:25',
            'address'   => 'max:60',
            'sort'      => 'required|max:10',
            'date_begin'=> 'required',
            'isActive'  => 'required',
            'roles'     => 'required'
        ]);

        $user = User::findOrFail($request->input("user_id"));

        if($request->input('username') != $user->username){

            $this->validate($request, [
                'username'  => 'required|unique:users',
            ]);
        }

        if($request->input('password')){

            $this->validate($request, [
                'password'  => 'confirmed|min:6',
            ]);
        }

        if(!empty($request->input('password'))){
            $password = Hash::make($request->input('password')); //update the password
            $user->update([
                $user->username = $request->input("username"),
                $user->password = $password,
                $user->status   = $request->input("status"),
                $user->isUw   = $request->input("isUw")

            ]);
        }else{
            $user->update([
                $user->username = $request->input("username"),
                $user->status   = $request->input("status"),
                $user->isUw   = $request->input("isUw")
            ]);
        }

        $personal_user = MPersonalUsers::where('user_id', $user->id)->first();

        if($personal_user != null && $request->input('email') != $personal_user->email){

            $this->validate($request, [
                'email'  => 'required|email|unique:m_personal_users'
            ]);
            // update m_personal_users
            $personal_users_input = $request->only('user_id', 'f_name', 'l_name', 'm_name','birthday', 'email', 'address');
            $personal = MPersonalUsers::where('user_id', $request->input('user_id'))->first();
            $personal->update($personal_users_input);
        }else{
            $personal_users_input = $request->only('user_id', 'f_name', 'l_name', 'm_name','birthday', 'email', 'address');
            $new_personal = MPersonalUsers::firstOrCreate($personal_users_input);
        }

        $w_user_id = $request->input('w_user_id');

        // update m_user_roles
        $roles = $request->input('roles');

        MUserRoles::where('user_id', $w_user_id)->delete();

        foreach ($roles as $key => $value) {
            $insert_roles = new MUserRoles();
            $insert_roles->user_id = $w_user_id;
            $insert_roles->role_id = $value;
            $insert_roles->save();
        }

        // update m_work_users
        $work_users_input = $request->only('date_begin','date_end', 'ip_phone', 'mob_phone', 'sort', 'isActive');

        MWorkUsers::find($w_user_id)->update($work_users_input);

        return back()->with('success','Xodim muvaffaqiyatli yangilandi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);

        $user->update([
            'status' => 2

        ]);

        return back()->with('success', 'Xodim muvaffaqiyatli o`chirildi');

    }

    public function usernameCheck($username)
    {
        $user = User::where('username', $username)->first();

        return response()->json($user, 200);
    }

}
