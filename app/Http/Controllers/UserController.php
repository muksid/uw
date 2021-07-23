<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

use App\User;
use App\Role;
use App\Department;
use App\MessageUsers;
use App\MWorkUsers;
use App\MPersonalUsers;
use App\MUserRoles;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function getTest()
    {
        //MUserRoles::where('user_id', '!=', 3633)->delete();
        /*us  = MWorkUsers::all();
        foreach ($us as $u){
            $role  = new MUserRoles();
            $role->user_id = $u->id;
            $role->role_id = 2;
            $role->isActive = 'A';
            $role->save();
        }*/
        print_r('test'); die;
        /*$db_ext = DB::connection('mysql_uw');
        $on_uw = $db_ext->table('users')->get();
        foreach ($on_uw as $value){
            $new_user = User::where('username', $value->username)->first();
            $new_user->update(['old_user_id' => $value->id]);
        }*/

        //print_r($on_uw); die;
        /*MWorkUsers::where('user_id', '!=', 1600)->delete();
        foreach ($on_uw as $value){
            $new_user = new MWorkUsers();
            $dep = Department::find($value->depart_id);
            if ($dep){
                $dep1 = $dep->depart_id;
            } else{
                $dep1 = 0;
            }
            $new_user->user_id = $value->id;
            $new_user->branch_code = $value->branch_code;
            $new_user->tab_num = $value->card_num;
            $new_user->gen_dep_id = $dep1;
            $new_user->depart_id = $value->depart_id;
            $new_user->job_title = $value->job_title;
            $new_user->date_begin = $value->created_at;
            $new_user->sort = 0;
            $new_user->isActive = 'A';
            $new_user->save();
        }*/
        /*MPersonalUsers::where('user_id', '!=', 1600)->delete();
        foreach ($on_uw as $value){
            $new_user = new MPersonalUsers();
            $new_user->user_id = $value->id;
            $new_user->f_name = $value->fname;
            $new_user->l_name = $value->lname;
            $new_user->m_name = $value->sname;
            $new_user->email = $value->email;
            $new_user->save();
        }*/
        //$on_uw = $db_ext->table('users')->get();
        /*User::where('id', '!=', 1600)->delete();
        foreach ($on_uw as $value){
         $new_user = new User();
         $new_user->username = $value->username;
         $new_user->email = $value->username;
         $new_user->password = $value->password;
         $new_user->remember_token = $value->remember_token;
         $new_user->status = $value->status;
         $new_user->created_at = $value->created_at;
         $new_user->updated_at = $value->updated_at;
         $new_user->last_login = $value->last_login;
         $new_user->last_login_ip = $value->last_login_ip;
         $new_user->isUw = 0;
         $new_user->save();
        }*/
    }

    public function index(Request $request)
    {
        $filials = Department::where('parent_id', 0)->where('status', 1)->orderBy('branch_code', 'ASC')->get();

        /*$models = User::with('personal', 'currentWork')
            ->orderBy('isUw','DESC')
            ->orderBy('created_at','DESC')
            ->paginate(20);

        return view('users.index', compact('models', 'filials'));*/

        $search = User::orderBy('id', 'DESC');

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $uw = Input::get ( 'uw' );

        if($u) {
            $search ->whereHas('currentWork', function ($query) use($u){
                $query->where('id', $u);
            });
        }

        if($uw) {
            $search ->where('isUw', $uw);
        }

        if($t) {
            $search->where('username', 'LIKE', '%' . $t . '%');
            $search ->orWhereHas('personal', function ($query) use($t){
                $query->whereRaw("concat(l_name, ' ', f_name) like '%".$t."%' ");
            });
        }

        $models = $search->paginate(20);

        $models->appends ( array (
            'u' => Input::get ( 'u' ),
            't' => Input::get ( 't' )
        ) );

        return view('users.index',
            compact('models','filials','t', 'uw'))
            ->withDetails ( $models )->withQuery ( $u, $t );

    }

    public function search(Request $request)
    {

        $f = $request->filial;

        $t = $request->text;

        if ($t !='' || $f !='')
        {

            $search = User::with('personal', 'currentWork')->orderBy('created_at', 'DESC');

            if($t) {
                $search->where('username', 'LIKE', '%' . $t . '%');
                $search ->orWhereHas('personal', function ($query) use($t){
                    $query->whereRaw("concat(l_name, ' ', f_name) like '%".$t."%' ");
                });
            }

            if($f) {
                $search ->whereHas('currentWork', function ($query) use($f){
                    $query->where('branch_code', ''.$f.'');
                });
            }

            $models = $search->paginate(20);

            $page = 'users.search_temp';

            if ($request->page){

                $page = 'users.index';
                if (count ( $models ) > 0)
                    return view ( $page,
                        compact('models','t','f'));
            }

            return view($page,compact('models'));

        }
        else
        {
            $filials = Department::where('parent_id', 0)->where('status', 1)->orderBy('branch_code', 'ASC')->get();

            $models = User::with('personal', 'currentWork')
                ->orderBy('created_at', 'DESC')
                ->paginate(20);

            return view('users.search_temp', compact('models', 'filials'));

        }
    }

    public function search1()
    {
        $models = User::orderBy('created_at', 'DESC')->paginate(25);

        $filial = Department::where('parent_id', 0)->where('status', 1)->orderBy('id','ASC')->get();

        $q = Input::get ( 'q' );
        $f = Input::get ( 'f' );
        $s = Input::get ( 's' );

        $f_title = Department::select('title')->where('branch_code', $f)->where('parent_id', '0')->first();

        if (!empty($f_title))
        {
            $f_title = $f_title->title;

        } else {
            $f_title = '';
        }

        if($q != '' or $f != '' or $s != ''){

            $models = User::with('personal')->where(function ($query) use($q) {
                $query->whereRaw("concat(l_name, ' ', f_name) like '%".$q."%' ")
                    ->orWhere('username', 'like', '%' . $q . '%');
            })
                ->where('branch_code', 'LIKE', '%'.$f.'%')
                ->where('status', 'LIKE', '%'.$s.'%')
                ->orderBy('created_at', 'DESC')
                ->paginate(25);

            $models->appends ( array (
                'q' => Input::get ( 'q' ),
                'f' => Input::get ( 'f' ),
                's' => Input::get ( 's' )
            ) );

            if (count ( $models ) > 0)
                return view ( 'users.index',
                    compact('models','q','f','s','f_title','filial'))

                    ->withDetails ( $models )->withQuery ( $q );
        }

        return view('users.index',
            compact('models','q','f','s','f_title','filial'))
            ->with('i', (request()->input('page', 1) - 1) * 25);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            return view('users.create',compact('roles', 'filials'));

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

            return view('users.create',compact('roles', 'filials'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

        return redirect()->route('users.index')
        ->with('success','Xodim muvaffaqiyatli yangilandi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Message $message)
    {
        return view('messages.show',compact('message'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit($id)
    {
        $work_user = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();

        $user_roles = DB::table('m_user_roles as a')
            ->join('roles as r', 'r.id', 'a.role_id')
            ->where('a.user_id', $work_user->id)
            ->where('a.isActive', 'A')
            ->groupBy('a.role_id')
            ->get();

        //print_r($user_roles); die;

        $user_arr_roles = array();
        if($user_roles){
            foreach ($user_roles as $key => $value) {
                array_push($user_arr_roles, $value->role_code);
            }
        }
//print_r($user_arr_roles[2]); die;
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

            return view('users.edit',
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

            return view('users.edit',
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
                'fname' => 'required|max:25',
                'password' => 'confirmed'
            ]);

            $input = $request->only('fname', 'lname', 'sname', 'job_title', 'password');

            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']); //update the password
            }else{
                $input = array_except($input,array('password')); //remove password from the input array
            }

            $user->update($input); //update the user info

            // add user table in Uw
            $db_uw = DB::connection('mysql_uw');

            $uw_tb = $db_uw->table('users')->where('id', $id)->update($input);

            return back()->with('success', 'Sizning profilingiz muvaffaqiyatli yangilandi');

        } else{

            if($user->username != $request->username){
                $this->validate($request, [
                    'username'  => 'required|max:25|unique:users'
                ]);
            }

            if($user->card_num != $request->card_num){
                $this->validate($request, [
                    'card_num'  => 'required|max:15|unique:users'
                ]);
            }

            $this->validate($request, [
                'fname'     => 'required|max:25',
                'password'  => 'confirmed',
                'roles'     => 'required',
                'user_sort' => 'required|max:3'
            ]);


            $input = $request->only('fname', 'lname', 'sname', 'job_title', 'branch_code', 'card_num', 'username',
                'email', 'password','depart_id', 'job_date', 'status','user_sort');

            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = array_except($input,array('password'));
            }

            foreach ($request->input('roles') as $value) {
                # code...
                $data_user[] = $value;
            }

            $input['roles'] = json_encode($data_user);

            // add user table in Uw
            $db_uw = DB::connection('mysql_uw');

            $uw_tb = $db_uw->table('users')->where('id', $id)->update($input);

            $user->update($input);

            return redirect()->route('users.index')
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
