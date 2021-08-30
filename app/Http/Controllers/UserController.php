<?php

namespace App\Http\Controllers;

use App\RoleDepartments;
use App\SDepartments;
use App\UwClients;
use App\UwJurClientComment;
use App\UwJuridicalClient;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Department;
use App\MWorkUsers;
use App\MPersonalUsers;
use App\MUserRoles;
use DB;
use Illuminate\Support\Facades\Hash;
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
        $s = Input::get ( 's' );
        $r = Input::get ( 'r' );

        if($u) {
            $search->whereHas('currentWork', function ($query) use($u){
                $query->where('id', $u);
            });
        }

        if($s) {
            $search->where('isActive', '=', $s);
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

            $search->where('username', 'LIKE', '%' . $this->transliterate($t) . '%');
            $search->orWhereHas('personal', function ($query) use($t){
                $query->whereRaw("concat(l_name, ' ', f_name) like '%".$this->transliterate($t)."%' ");
            });
        }

        $models = $search->paginate(20);

        $models->appends ( array (
            'u' => Input::get ( 'u' ),
            'r' => Input::get ( 'r' ),
            't' => Input::get ( 't' )
        ) );

        return view('madmin.users.index',
            compact('models','filials','roles','t', 's', 'r'))
            ->withDetails ( $models )->withQuery ( $u, $t );

    }

    public function edit($id)
    {

        $checkAdminRoles = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();

        if ($checkAdminRoles) {

            $adminRoles = MUserRoles::select('m_user_roles.*', 'roles.*')
                ->leftJoin('roles', function($join) {
                    $join->on('m_user_roles.role_id', '=', 'roles.id');
                })
                ->where('m_user_roles.user_id', $checkAdminRoles->id)
                ->where('m_user_roles.isActive', 'A')
                ->whereIn('roles.role_code', ['madmin', 'uw_admin'])
                ->groupBy('m_user_roles.role_id')
                ->get();

            $arrayAdminRoles = array();

            if($adminRoles){
                foreach ($adminRoles as $key => $value) {
                    array_push($arrayAdminRoles, $value->role_code);
                }
            }


            if(in_array( 'madmin', $arrayAdminRoles))
            {

                $userWorkRoles = Role::all();

            } elseif ($arrayAdminRoles[2] = 'uw_admin '){

                $userWorkRoles = Role::whereNotIn('role_code', ['madmin', 'uw_admin'])->get();

            }

            $user = User::findOrFail($id);

            $personal_user  = MPersonalUsers::where('user_id', $user->id)->first();

            $user_history_works = MWorkUsers::where('user_id', $user->id)->orderBy('isActive')->get();

            $current_work_user = MWorkUsers::where('user_id', $user->id)->where('isActive', 'A')->first();

            if ($current_work_user) {

                $userWorkCurrentRoles = MUserRoles::select('m_user_roles.*', 'roles.*')
                    ->leftJoin('roles', function($join) {
                        $join->on('m_user_roles.role_id', '=', 'roles.id');
                    })
                    ->where('m_user_roles.user_id', $current_work_user->id)
                    ->get();

            } else {

                $userWorkCurrentRoles = MUserRoles::select('m_user_roles.*', 'roles.*')
                    ->leftJoin('roles', function($join) {
                        $join->on('m_user_roles.role_id', '=', 'roles.id');
                    })
                    ->where('m_user_roles.user_id', 0)
                    ->get();

            }

            //print_r($current_work_user->user_id); die;

            return view('madmin.users.edit',
                compact('user','personal_user','user_history_works','userWorkRoles', 'userWorkCurrentRoles', 'current_work_user'));


        } else {

            return view('errors.' . '404');

        }
    }

    public function usernameCheck($username)
    {
        $user = User::where('username', $username)->first();

        return response()->json($user, 200);
    }

    public function oraIndex()
    {

        return view('madmin.users.ora.index');

    }

    public function getUserInfo($cb_id)
    {

        $checkUser = User::where('cb_id', $cb_id)->first();

        if ($checkUser) {
            $isUser = 'Yes';

            $user_id = $checkUser->id;

            return response()->json(['data' => '', 'isUser' => $isUser, 'user_id' => $user_id]);

        } else {

            $array = array('cb_id' => $cb_id, 'type' => 'get_emp_insert');

            $oraEmp =  UwJuridicalClientsController::curlHttpPost($array);

            if ($oraEmp) {

                $oraEmpDecode = json_decode($oraEmp, true);

                $oraValue = $oraEmpDecode[0];

                $isUser = 'No';
                $user_id = 0;

                return response()->json(['data' => $oraValue, 'isUser' => $isUser, 'user_id' => $user_id]);
            }
            return response()->json(['data' => null]);
        }

    }

    public function updateUserInfo($cb_id)
    {

        $array = array('cb_id' => $cb_id, 'type' => 'emp_upd_personal');

        $oraEmp =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraEmp) {

            $oraEmpDecode = json_decode($oraEmp, true);

            $oraValue = $oraEmpDecode[0];

            $user = User::where('cb_id', $cb_id)->first();

            $user_personal = MPersonalUsers::where('user_id', $user->id)->first();

            if ($user_personal) {

                $user_personal->update([
                    'emp_id' => $oraValue['emp_id'],
                    'f_name' => $oraValue['first_name'],
                    'l_name' => $oraValue['last_name'],
                    'm_name' => $oraValue['middle_name'],
                    'birthday' => date('Y-m-d', strtotime($oraValue['birth_date'])),
                    'email' => $oraValue['mail_address'],
                    'pinfl' => $oraValue['inps'],
                    'inn' => $oraValue['inn'],
                    'doc_series' => $oraValue['passport_seria'],
                    'doc_number' => $oraValue['passport_number'],
                    'doc_begin_date' => date('Y-m-d', strtotime($oraValue['passport_date_begin'])),
                    'doc_end_date' => date('Y-m-d', strtotime($oraValue['passport_date_end'])),
                    'doc_address' => $oraValue['passport_issued'],
                    'mobile_phone' => $oraValue['phone'],
                    'address' => $oraValue['address'],
                ]);

            } else {

                $create_user_personal = new MPersonalUsers();
                $create_user_personal->user_id = $user->id;
                $create_user_personal->emp_id = $oraValue['emp_id'];
                $create_user_personal->l_name = $oraValue['last_name'];
                $create_user_personal->f_name = $oraValue['first_name'];
                $create_user_personal->m_name = $oraValue['middle_name'];
                $create_user_personal->birthday = date('Y-m-d', strtotime($oraValue['birth_date']));
                $create_user_personal->pinfl = $oraValue['inps'];
                $create_user_personal->inn = $oraValue['inn'];
                $create_user_personal->doc_series = $oraValue['passport_seria'];
                $create_user_personal->doc_number = $oraValue['passport_number'];
                $create_user_personal->doc_begin_date = date('Y-m-d', strtotime($oraValue['passport_date_begin']));
                $create_user_personal->doc_end_date = date('Y-m-d', strtotime($oraValue['passport_date_end']));
                $create_user_personal->doc_address = $oraValue['passport_issued'];
                $create_user_personal->mobile_phone = $oraValue['phone_mobil2'];
                $create_user_personal->email = $oraValue['mail_address'];
                $create_user_personal->address = $oraValue['address'];
                $create_user_personal->save();
            }

        }

        return back()->with('success', 'Sizning ma`lumotlaringiz IABS tizimidan muvaffaqiyatli yangilandi');

    }

    public function updateUserWork($user_id)
    {

        $user = User::findOrFail($user_id);

        if ($user->cb_id != 0){

            $array = array('cb_id' => $user->cb_id, 'type' => 'emp_get_last_work');

            $oraEmp =  UwJuridicalClientsController::curlHttpPost($array);

            if ($oraEmp) {

                $oraEmpDecode = json_decode($oraEmp, true);

                if (!$oraEmpDecode) {

                    $user->update(['isActive' => 'D']);

                    return back()->with('success', 'Tizimidan ma`lumot topilmadi!!! (Уволенные)');

                } else {


                $oraValue = $oraEmpDecode[0];

                $checkUser = MWorkUsers::where('emp_id', '=', $oraValue['emp_id'])
                    ->where('user_id', $user->id)
                    ->where('depart_code', '=', $oraValue['code'])
                    ->where(DB::raw("(STR_TO_DATE(date_begin,'%Y-%m-%d'))"), "=", date('Y-m-d', strtotime($oraValue['begin_date'])))
                    ->first();

                if (!$checkUser) {
                    $old_works = MWorkUsers::where('user_id', '=', $user_id)->get();
                    if ($old_works){
                        foreach ($old_works as $old_work) {
                            $old_work->update(['isActive' => 'P']);
                        }
                    }

                    $local_code = $oraValue['filial'];
                    $sDepartment = SDepartments::where('code', '=', $oraValue['code'])->where('local_code', '!=', 'F')->first();
                    if ($sDepartment) {
                        $local_code = $sDepartment->local_code;
                    }
                    $create_user_work = new MWorkUsers();
                    $create_user_work->user_id = $user_id;
                    $create_user_work->emp_id = $oraValue['emp_id'];
                    $create_user_work->tab_num = $oraValue['tab_num'];
                    $create_user_work->branch_code = $oraValue['filial'];
                    $create_user_work->local_code = $local_code;
                    $create_user_work->parent_code = $oraValue['parent_code'];
                    $create_user_work->depart_code = $oraValue['code'];
                    $create_user_work->job_title = $oraValue['work_post'];
                    $create_user_work->date_begin = date('Y-m-d', strtotime($oraValue['begin_date']));
                    $create_user_work->isActive = 'A';
                    $create_user_work->save();

                    // CREATE ROLE
                    $oldWorkUser = MWorkUsers::where('user_id', '=', $user_id)->where('isActive', '=', 'P')->orderBy('created_at', 'DESC')->first();
                    //print_r($oldWorkUser); die;
                    if ($oldWorkUser) {
                        $oldRoles = MUserRoles::where('user_id', $oldWorkUser->id)->get();
                        if ($oldRoles) {
                            foreach ($oldRoles as $index => $oldRole) {
                                $role = new MUserRoles();
                                $role->user_id = $create_user_work->id;
                                $role->role_id = $oldRole->role_id;
                                $role->isActive = 'A';
                                $role->save();
                            }
                        } else {
                            $role = new MUserRoles();
                            $role->user_id = $create_user_work->id;
                            $role->role_id = 28;
                            $role->isActive = 'A';
                            $role->save();
                        }

                    } else {

                        $role = new MUserRoles();
                        $role->user_id = $create_user_work->id;
                        $role->role_id = 28;
                        $role->isActive = 'A';
                        $role->save();

                    }

                }

                return $this->updateUserInfo($user->cb_id);
                }
            }
        } else {

            return back()->with('success', 'CB 0 !!!');

        }

        return back()->with('success', 'Tizimidan ma`lumot topilmadi!!!');

    }

    public function storeNew(Request $request)
    {
        $cb_id = $request->cb_id;

        $array = array('cb_id' => $cb_id, 'type' => 'get_emp_insert');

        $oraEmp =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraEmp) {

            $oraEmpDecode = json_decode($oraEmp, true);

            $oraValue = $oraEmpDecode[0];

            if ($oraValue) {

                $department = RoleDepartments::where('filial_code', '=', $oraValue['filial'])
                    ->where('code', '=', $oraValue['department_code'])
                    ->where('isActive', '=', 'A')
                    ->first();

                if (!$department) {

                    return back()->with('success', 'Xodimning departament yoki bo`limi topilmadi!!!');

                }
                $this->validate($request, [
                    'username'  => 'required|min:3|unique:users,username',
                ]);

                $create_user = new User();
                $create_user->username = $request->username;
                $create_user->password = Hash::make($request->username);
                $create_user->cb_id = $oraValue['cb_id'];
                $create_user->isActive = 'A';
                $create_user->save();

                $create_user_personal = new MPersonalUsers();
                $create_user_personal->user_id = $create_user->id;
                $create_user_personal->emp_id = $oraValue['emp_id'];
                $create_user_personal->l_name = $oraValue['last_name'];
                $create_user_personal->f_name = $oraValue['first_name'];
                $create_user_personal->m_name = $oraValue['middle_name'];
                $create_user_personal->birthday = date('Y-m-d', strtotime($oraValue['birth_date']));
                $create_user_personal->pinfl = $oraValue['inps'];
                $create_user_personal->inn = $oraValue['inn'];
                $create_user_personal->doc_series = $oraValue['passport_seria'];
                $create_user_personal->doc_number = $oraValue['passport_number'];
                $create_user_personal->doc_begin_date = date('Y-m-d', strtotime($oraValue['passport_date_begin']));
                $create_user_personal->doc_end_date = date('Y-m-d', strtotime($oraValue['passport_date_end']));
                $create_user_personal->doc_address = $oraValue['passport_issued'];
                $create_user_personal->mobile_phone = $oraValue['phone_mobil2'];
                $create_user_personal->email = $oraValue['mail_address'];
                $create_user_personal->address = $oraValue['address'];
                $create_user_personal->save();

                $create_user_work = new MWorkUsers();
                $create_user_work->user_id = $create_user->id;
                $create_user_work->emp_id = $oraValue['emp_id'];
                $create_user_work->branch_code = $oraValue['filial'];
                $create_user_work->local_code = $oraValue['filial'];
                $create_user_work->parent_code = $department->parent_code;
                $create_user_work->depart_code = $oraValue['department_code'];
                $create_user_work->tab_num = $oraValue['tab_num'];
                $create_user_work->job_title = $oraValue['work_post'];
                $create_user_work->date_begin = date('Y-m-d', strtotime($oraValue['begin_work_date']));
                $create_user_work->isActive = 'A';
                $create_user_work->save();

                foreach ($request->roles as $role)
                {
                    $create_user_role = new MUserRoles();
                    $create_user_role->user_id = $create_user_work->id;
                    $create_user_role->role_id = $role;
                    $create_user_role->isActive = 'A';
                    $create_user_role->save();

                }

                return redirect(route('users.index'))->with('success', 'Xodim muvaffaqiyatli qo`shildi');

            }

            return back()->with('success', 'Xodim qo`shishda xatolik mavjud!!!');

        }

        return back()->with('success', 'Xodim qo`shishda xatolik mavjud!!!');

    }

    public function usernameUpdate(Request $request, $user_id)
    {
        //
        $this->validate($request, [
            'username' => 'required|min:3',
        ]);

        $username = $request->input('username');

        $password = $request->input('password');

        $passwordConf = $request->input('passwordConf');

        $isActive = $request->isActive;

        $user = User::findOrFail($user_id);

        if($username != $user->username && !trim($password)){

            $this->validate($request, [
                'password' => 'required',
                'passwordConf' => 'required',
                'username'  => 'required|min:3|unique:users,username',
            ]);

        } elseif ($password != $passwordConf ) {

            $this->validate($request, [
                'password'  => 'confirmed|min:6',
            ]);

        } else {

            if (trim($password) == ''){

                $user->update([
                    $user->isActive = $isActive
                ]);

            } else {

                $this->validate($request, [
                    'password' => 'required',
                    'passwordConf' => 'required'
                ]);

                $password = Hash::make($password);

                $user->update([
                    $user->username = $username,
                    $user->password = $password,
                    $user->isActive = $isActive,

                ]);

            }

            return back()->with('success', 'Xodim Username, kalit so`zi muvaffaqiyatli yangilandi');
        }


    }

    public function updateUserRoles(Request $request, $user_id)
    {

        MUserRoles::where('user_id', $user_id)->delete();

        $roles = $request->roles;

        foreach ($roles as $key => $value) {
            $insert_roles = new MUserRoles();
            $insert_roles->user_id = $user_id;
            $insert_roles->role_id = $value;
            $insert_roles->save();
        }

        return back()->with('success', 'User Roles updated');

    }

    public function profile($id)
    {

        $user = User::findOrFail(Auth::id());


        return view('madmin.users.profile',compact('user'));

    }

    public function profileUpdate(Request $request, $user_id)
    {
        //
        $this->validate($request, [
            'password' => 'required|min:6',
            'passwordConf' => 'required|min:6',
        ]);

        $username = $request->input('username');

        $password = $request->input('password');

        $user = User::findOrFail(Auth::id());

        if ($username != $user->username) {
            $this->validate($request, [
                'password' => 'required|min:6',
                'passwordConf' => 'required|min:6',
                'username'  => 'required|min:3|unique:users,username',
            ]);
        }

        $password = Hash::make($password);
        $user->update([
            'username' => $username,
            'password' => $password,
        ]);

        return back()->with('success', 'Xodim Username, kalit so`zi muvaffaqiyatli yangilandi');


    }

    public function destroy($id)
    {
        //
        $user = User::find($id);

        $checkWorkUser = MWorkUsers::where('user_id', $user->id)->first();

        $status = 'P';
        $data = '';
        if ($checkWorkUser) {
            //$status = 'A';
            $checkPhyClient = UwClients::where('work_user_id', $checkWorkUser->id)->first();
            if ($checkPhyClient) {
                $status = 'A';
                $data = $checkPhyClient;
                $checkJurClient = UwJuridicalClient::where('work_user_id', $checkWorkUser->id)->first();

                if ($checkJurClient) {
                    $status = 'A';
                    $data = $checkJurClient;
                    $checkPhyClientComment = UwJurClientComment::where('work_user_id', $checkWorkUser->id)->first();

                    if ($checkPhyClientComment) {
                        $status = 'A';
                        $data = $checkPhyClientComment;
                        $checkJurClientComment = UwJurClientComment::where('work_user_id', $checkWorkUser->id)->first();

                        if ($checkJurClientComment) {
                            $status = 'A';
                            $data = $checkJurClientComment;
                        }

                    }

                }

            }
            //print_r($status); die;
            //print_r($status); die;

            if ($status == 'P') {

                $checkWorkUserRole = MUserRoles::where('user_id', $checkWorkUser->id)->get();

                if ($checkWorkUserRole) {
                    MUserRoles::where('user_id', $checkWorkUser->id)->delete();
                }
                MWorkUsers::where('user_id', $user->id)->delete();

                MPersonalUsers::where('user_id', $user->id)->delete();

                User::find($id)->delete();

                return response()->json(['success' => 'Xodim muvaffaqiyatli o`chirildi', 'status' => $status, 'data' => $data]);
            }

            return response()->json(['success' => 'Xatolik mavjud!!!', 'status' => $status, 'data' => $data]);

        } else {

            MPersonalUsers::where('user_id', $user->id)->delete();

            User::find($id)->delete();

            return response()->json(['success' => 'Xodim muvaffaqiyatli o`chirildi', 'status' => $status, 'data' => $data]);
        }

        //return response()->json(['success' => 'Xatolik mavjud!!!', 'status' => $status]);

    }

    public function transliterate( $textlat = null, $textcyr = null) {
        $lat = array(
            'j', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ya', 'x','q','g',
            'J', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'ts', 'Ya', 'X','Q','G');
        $cyr = array(
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ҳ', 'ц', 'я', 'Х', 'қ', 'ғ',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ҳ', 'Ц', 'Я', 'Х', 'Қ', 'Ғ');
        if($textlat) return str_replace($lat, $cyr, $textlat);
        else if($textlat) return str_replace($cyr, $lat, $textcyr);
        else return null;
    }


    public function create()
        {

            $work_user = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->firstOrFail();

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

                return view('madmin.users.create',compact('roles'));

            }
            elseif(in_array( 'uw_admin', $user_arr_roles))
            {

                $roles = Role::whereNotIn('role_code', ['madmin', 'uw_admin'])->get();

                return view('madmin.users.create',compact('roles'));
            }
            else
            {
                return response()->view('errors.' . '404', [], 404);
            }
        }
/*
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

            $checkAdminRoles = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();

            if ($checkAdminRoles) {

                $adminRoles = MUserRoles::select('m_user_roles.*', 'roles.*')
                    ->leftJoin('roles', function($join) {
                        $join->on('m_user_roles.role_id', '=', 'roles.id');
                    })
                    ->where('m_user_roles.user_id', $checkAdminRoles->id)
                    ->where('m_user_roles.isActive', 'A')
                    ->whereIn('roles.role_code', ['madmin', 'uw_admin'])
                    ->groupBy('m_user_roles.role_id')
                    ->get();

                $arrayAdminRoles = array();

                if($adminRoles){
                    foreach ($adminRoles as $key => $value) {
                        array_push($arrayAdminRoles, $value->role_code);
                    }
                }


                if(in_array( 'madmin', $arrayAdminRoles))
                {

                    $userWorkRoles = Role::all();

                } elseif ($arrayAdminRoles[2] = 'uw_admin '){

                    $userWorkRoles = Role::where('role_code', '!=', 'madmin')->get();
                }

                $user = User::findOrFail($id);

                $personal_user  = MPersonalUsers::where('user_id', $user->id)->first();

                $user_history_works = MWorkUsers::where('user_id', $user->id)->orderBy('isActive')->get();

                $current_work_user = MWorkUsers::where('user_id', $user->id)->where('isActive', 'A')->first();

                if ($current_work_user) {

                    $userWorkCurrentRoles = MUserRoles::select('m_user_roles.*', 'roles.*')
                        ->leftJoin('roles', function($join) {
                            $join->on('m_user_roles.role_id', '=', 'roles.id');
                        })
                        ->where('m_user_roles.user_id', $current_work_user->id)
                        ->get();

                } else {

                    $userWorkCurrentRoles = MUserRoles::select('m_user_roles.*', 'roles.*')
                        ->leftJoin('roles', function($join) {
                            $join->on('m_user_roles.role_id', '=', 'roles.id');
                        })
                        ->where('m_user_roles.user_id', 0)
                        ->get();

                }

                //print_r($current_work_user->user_id); die;

                return view('madmin.users.edit',
                    compact('user','personal_user','user_history_works','userWorkRoles', 'userWorkCurrentRoles', 'current_work_user'));


            } else {

                return view('errors.' . '404');

            }
        }

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
        }*/

}
