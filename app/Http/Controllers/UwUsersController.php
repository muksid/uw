<?php

namespace App\Http\Controllers;

use App\Filials;
use App\Role;
use App\User;
use App\UwUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UwUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $admin = UwUsers::where('user_id', Auth::id())->where('status', 1)->firstorFail();
        $admin_role = Role::find($admin->role_id);
        if ($admin_role->role_code == 'super_admin' || $admin_role->role_code == 'risk_adminstrator') {

            $models = UwUsers::orderBy('id', 'ASC')->get();

            $users = User::where('status', 1)
                ->orderBy('id', 'ASC')->get();

            $filials = Filials::where('parent_id', '>', 0)->where('status', 1)
                ->orderBy('id', 'ASC')->get();

            $roles = Role::where('title_ru', 'like', '%uw%')->get();

            return view('uw.users.index', compact('models', 'users', 'filials', 'roles'));

        } else {

            return redirect('/uw/home');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $row_id = $request->model_id;

/*        $check_user = UwUsers::where('user_id', $request->user_id)->first();
        if ($check_user){
            return response()->json(array('error' => 'User already inserted'));
        }*/

        UwUsers::updateOrCreate(['id' => $row_id],
            [
                'user_id' => $request->user_id,
                'filial_id' => $request->filial_id,
                'role_id' => $request->role_id,
                'status' => $request->status
            ]);

        $user = User::find($request->user_id);

        $filial = Filials::find($request->filial_id);

        $role = Role::find($request->role_id);

        return response()->json(array(
                'success' => true,
                'user_name' => $user->branch_code.' '.$user->fname.' '.$user->lname,
                'filial_name' => $filial->filial->title??'',
                'bxo_name' => $filial->title,
                'role_name' => $role->title,
                'job_name' => $user->job_title,
                'user_id' => $user->id,
                'filial_id' => $filial->id,
                'role_id' => $role->id,
                'status' => $request->status,
                'created_at' => date("d.m.Y H:i"),
                'id' => 100
            )
        );

        //return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $uw_user = UwUsers::find($id);

        $user = User::find($uw_user->user_id);

        $filial = Filials::find($uw_user->filial_id);

        $role = Role::find($uw_user->role_id);

        return response()->json(array(
            'success' => true,
            'user_name' => $user->branch_code.' '.$user->fname.' '.$user->lname,
            'filial_name' => $filial->title,
            'role_name' => $role->title,
            'user_id' => $user->id,
            'filial_id' => $filial->id,
            'role_id' => $role->id,
            'status' => $uw_user->status
            )
        );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = UwUsers::find($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //UwUsers::find($id)->delete();

        return response()->json(['success'=>'Filial Deleted successfully']);
    }
}
