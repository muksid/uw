<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

use App\User;
use App\Role;
use App\Department;
use App\MessageUsers;
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

    public function index(Request $request)
    {
        //
        foreach (json_decode(Auth::user()->roles) as $user){

            switch($user){
                case('admin'):
                case('main_staff'):

                    $models = User::orderBy('created_at', 'DESC')->paginate(25);

                    break;

                case('branch_admin'):

                    $models = User::where('branch_code', Auth::user()->branch_code)
                        ->whereNotIn('status', [2])
                        ->orderBy('created_at', 'DESC')
                        ->paginate(25);

                    break;

                default:

                    return response()->view('errors.' . '404', [], 404);

                    break;

            }
        }

        // count() //
        @include('count_message.php');

        $filial = Department::where('parent_id', 0)->where('status', 1)->orderBy('id','ASC')->get();

        $q = Input::get ( 'q' );
        $f = Input::get ( 'f' );
        $s = Input::get ( 's' );
        $f_title = '';

        return view('users.index',
            compact('models','q','f','s','f_title','filial',
                'inbox_count','sent_count','term_inbox_count','all_inbox_count'))
            ->with('i', (request()->input('page', 1) - 1) * 25);
    }

    public function search()
    {

        // count() //
        @include('count_message.php');

        foreach (json_decode(Auth::user()->roles) as $user){
            switch($user){
                case('admin'):

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

                        $models = User::where(function ($query) use($q) {
                                    $query->whereRaw("concat(lname, ' ', fname, ' ', sname) like '%".$q."%' ")
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
                                compact('models','q','f','s','f_title','filial',
                                    'inbox_count','sent_count','term_inbox_count','all_inbox_count'))

                                ->withDetails ( $models )->withQuery ( $q );
                    }

                    break;

                case('branch_admin'):

                    $models = User::where('branch_code', Auth::user()->branch_code)
                        ->whereNotIn('status', [2])
                        ->orderBy('created_at', 'DESC')
                        ->paginate(25);

                    $filial = Department::where('parent_id', 0)
                        ->where('branch_code', Auth::user()->branch_code)
                        ->where('status', 1)->get();

                    $q = Input::get ( 'q' );
                    $f = Input::get ( 'f' );
                    $s = Input::get ( 's' );
                    $f_title = Department::select('title')->where('branch_code', $f)->where('parent_id', '0')->first();

                    if($q != '' or $f != '' or $s != '') {

                        $models = User::where(function ($query) use($q) {
                            $query->whereRaw("concat(lname, ' ', fname, ' ', sname) like '%".$q."%' ")
                                ->orWhere('username', 'like', '%' . $q . '%');
                        })
                            ->where('branch_code', Auth::user()->branch_code)
                            ->whereNotIn('status', [2])
                            ->where('status', 'LIKE', '%'.$s.'%')
                            ->orderBy('created_at', 'DESC')
                            ->paginate(25);

                        $models->appends(array(
                            'q' => Input::get('q'),
                            'f' => Input::get('f'),
                            's' => Input::get('s')
                        ));

                        if (count($models) > 0)
                            return view('users.index',
                                compact('models', 'q', 'f', 's','f_title','filial',
                                    'inbox_count', 'sent_count', 'term_inbox_count', 'all_inbox_count'))
                                ->withDetails($models)->withQuery($q);
                    }

                    break;
                default:
                    return response()->view('errors.' . '404', [], 404);
                    break;

            }
        }

        return view('users.index',
            compact('models','q','f','s','f_title','filial',
                'inbox_count','sent_count','term_inbox_count','all_inbox_count'))
            ->with('i', (request()->input('page', 1) - 1) * 25);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // count() //
        @include('count_message.php');

        foreach (json_decode(Auth::user()->roles) as $user){
            switch($user){
                case('admin'):

                    $roles = Role::all(); //get all roles

                    $filial = Department::where('parent_id', 0)
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();

                    $departments = Department::where('status', 1)->get();

                    return view('users.create',compact('roles', 'filial', 'departments',
                        'inbox_count','sent_count','term_inbox_count','all_inbox_count'));

                    break;
                case('branch_admin'):

                    $roles = Role::where('role_code', 'user')
                        ->orWhere('role_code', 'branch_admin')
                        ->orWhere('role_code', 'branch_staff')
                        ->get();

                    $filialDepartId = Department::where('branch_code', Auth::user()->branch_code)
                        ->where('parent_id',0)
                        ->where('status', 1)
                        ->first();

                    $departments = Department::where('parent_id', $filialDepartId->id)
                        ->where('branch_code', Auth::user()->branch_code)
                        ->where('status',  1)
                        ->get();

                    return view('users.create',compact('roles', 'departments',
                        'inbox_count','sent_count','term_inbox_count','all_inbox_count'));

                    break;
                default:
                    return response()->view('errors.' . '404', [], 404);
                    break;
            }
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'fname'         => 'required|max:25',
            'lname'         => 'required|max:25',
            'sname'         => 'max:50',
            'username'      => 'required|max:25|unique:users', // Jamshid unique added
            'card_num'      => 'required|max:15|unique:users', // Jamshid unique added //max: from 25 to 15 2020-06-08 09:34:05
            'branch_code'   => 'required|max:25',
            'depart_id'     => 'required|max:25',
            'job_title'     => 'required|max:70',
            'job_date'      => 'required',
            'password'      => 'required|confirmed|min:6',
            'user_sort'     => 'required|max:3'         // Jamshid added new field 2020-06-08 10:19:56

        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']); //Hash password
        $input['user_gen'] = md5($input['username']); //md5 username
        $input['email'] = $input['branch_code'].$input['card_num']."@tb.uz"; // Jamshid Default email   branch_code + card_num + @turonbak.uz
        foreach ($input['roles'] as $value) {
            # code...
            $data_user[] = $value;
        }
        $input['roles'] = json_encode($data_user);

        User::create($input);

        return back()->with('success', 'Xodim muvaffaqiyatli qo`shildi');
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

    //  Jamshid edited edit() function
    public function edit($id)
    {
        // count() //
        @include('count_message.php');

        foreach (json_decode(Auth::user()->roles) as $user_type){
            switch($user_type){
                case('admin'):

                    $user = User::where('id', $id)->firstOrFail();

                    $roles = Role::all(); //get all roles

                    $filial = Department::where('parent_id', '=', 0)
                        ->where('status', '=', 1)
                        ->orderBy('id','ASC')
                        ->get();

                    $departments = Department::where('status', '=', 1)
                        ->where('branch_code','=',$user->branch_code)
                        ->orderBy('id', 'ASC')
                        ->get();

                    return view('users.edit',
                        compact('user', 'filial','departments','roles',
                        'inbox_count','sent_count','term_inbox_count','all_inbox_count'));

                    break;
                case('branch_admin'):

                    $user = User::where('branch_code', Auth::user()->branch_code)
                        ->where('id', $id)
                        ->firstOrFail();

                    $filialDepartId = Department::where('branch_code', '=', Auth::user()->branch_code)
                        ->where('parent_id',0)
                        ->where('status', '=', 1)
                        ->first();

                    $departments = Department::where('parent_id', $filialDepartId->id)
                        ->where('branch_code', '=', Auth::user()->branch_code)
                        ->where('status', '=', 1)
                        ->get();

                    $roles = Role::where('role_code' , '=', 'user')
                        ->orWhere('role_code' , '=', 'branch_admin')
                        ->orWhere('role_code' , '=', 'branch_staff')
                        ->get();

                    return view('users.edit',compact('user','departments','roles',
                        'inbox_count','sent_count','term_inbox_count','all_inbox_count'));

                    break;
                case('user'):
                case('office'):
                    $user = User::find($id);
                    return view('users.edit',compact('user',
                        'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
                    break;
                default:

                    $user = User::find($id);
                    return view('users.edit',compact('user',
                        'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
                    break;
            }

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();

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
                $input['password'] = Hash::make($input['password']); //update the password
            }else{
                $input = array_except($input,array('password')); //remove password from the input array
            }

            foreach ($request->input('roles') as $value) {
                # code...
                $data_user[] = $value;
            }

            $input['roles'] = json_encode($data_user);

            $user->update($input); //update the user info

            return redirect()->route('users.index')
                ->with('success','Xodim muvaffaqiyatli yangilandi');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $user = User::where('id', $id)->firstOrFail();

        $user->update([
            'status' => 2

        ]);

        $messageUsers = MessageUsers::where('to_users_id', $user->id)->get();

        if (count($messageUsers))
        {
            foreach ($messageUsers as $message)
            {

                $message = MessageUsers::find($message->id);

                $message->update(['is_deleted' => 2]);
            }

        }

        return back()->with('deleted', 'Xodim muvaffaqiyatli o`chirildi');

    }

}
