<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MWorkUsers;
use App\MUserRoles;
use App\Department;
use DB;

class MWorkUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\RedirectResponse
     */
    // Add new position for user
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id'   => 'required',
            'depart_id' => 'required',
            'job_title' => 'required',
            'tab_num'   => 'required',
            'date_begin'=> 'required',
            'isActive'  => 'required',
            'sort'      => 'required',
            'roles'      => 'required'
        ]);
        $branch = Department::find($request->input('depart_id'));

        $model = MWorkUsers::firstOrCreate(
            array_merge($request->except(['_token','roles']),
            ['branch_code' => $branch->branch_code, 'gen_dep_id' => $branch->depart_id]));

        foreach ($request->input('roles') as $key => $value) {
            $role = new MUserRoles();
            $role->user_id = $model->id;
            $role->role_id = $value;
            $role->save();
        }

        if($request->input('isActive') === 'A'){
            MWorkUsers::where('user_id', $request->input('user_id'))
                ->where('id','!=', $model->id)
                ->where('isActive', 'A')
                ->update([ 'isActive' => 'P']);
        }

        return back()->with('success','Xodimga yangi ish joyi muvaffaqiyatli qo`shildi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }

    public function getRoles($id)
    {
        $user = MWorkUsers::where('user_id', $id)->where('isActive', 'A')->first();
        if($user === null)
            $user = MWorkUsers::where('user_id', $id)->orderBy('updated_at', 'DESC')->first();
        $models = DB::table('m_user_roles as a')
            ->join('roles as r', 'r.id', 'a.role_id')
            ->where('user_id', $user->id)
            ->get();

        return response()->json(['models' => $models, 'current_user' => $user], 200);
    }

    public function getHistory($id)
    {
        $models = DB::table('m_work_users as a')
            ->join('departments as d1', 'a.branch_code','d1.branch_code')
            ->join('departments as d2', 'd2.id', 'a.depart_id')
            ->select('a.*','d1.title as branch_title', 'd2.title as depart_title')
            ->where('a.user_id', $id)
            ->where('d1.parent_id', 0)
            ->orderBy('a.isActive', 'ASC')
            ->orderBy('a.updated_at', 'DESC')
            ->get();

        return response()->json($models, 200);
    }

    public function getHistoryRoles($id)
    {
        $models = DB::table('m_user_roles as a')
            ->join('roles as r', 'r.id', 'a.role_id')
            ->where('a.user_id', $id)
            ->where('a.isActive', 'A')
            ->get();
        return response()->json(['models' => $models, 'user_id' => $id], 200);
    }

    public function activateUser($id)
    {
        // $id is MWorkUsers's id
        $model = MWorkUsers::findOrFail($id);
        $activeUsers = MWorkUsers::where('user_id', $model->user_id)->where('isActive', 'A')->get();
        if($activeUsers != null){
            $updateUser = MWorkUsers::where('user_id', $model->user_id)->where('isActive', 'A')->update([
                'isActive' => 'P'
            ]);
        }
        sleep(1);
        $model->update([ 'isActive' => 'A']);
        $mes = "Successfully Activated!";

        return response()->json(['model' => $model, 'activeUsers' => $activeUsers, 'mes' => $mes], 200);
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
        dd("HOllaaaa");
        dd($request->all());
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
    }

    public function tab_numCheck($tab_num)
    {
        $user = MWorkUsers::where('tab_num', $tab_num)->first();

        return response()->json($user, 200);
    }
}
