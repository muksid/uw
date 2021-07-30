<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MMenuRoles;
use App\MRoleMenu;
use App\Role;
use DB;

class MRoleMenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $models = MRoleMenu::orderBy('role_id', 'ASC')->paginate(25);

        $menu_roles = MMenuRoles::with('coreMenu')->orderBy('created_at', 'DESC')->get();

        $roles = Role::get();

        @include('count_message.php');

        return view('control.menus.m-role-menus', compact('models','menu_roles','roles'))
            ->with('i', (request()->input('page', 1) - 1) * 50);
    }

    public function search(Request $request)
    {
        # code...
        $input = $request->input('input');
        $menu_roles = MMenuRoles::orderBy('created_at', 'DESC')->get();
        $roles = Role::get();

        $models = MRoleMenu::whereHas('userRole', function($query) use($input){
                $query->where('title','like', '%'.$input.'%');
            })
            ->orWhereHas('menuRole', function($query) use($input){
                $query->where('title','like', '%'.$input.'%');
            })
            ->orWhereHas('parentMenuRole', function($query) use($input){
                $query->where('title','like', '%'.$input.'%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        $models->appends ( array ('input' => $input) );

        @include('count_message.php');

        return view('control.menus.m-role-menus', compact('models','menu_roles','roles','input'))->with('i', (request()->input('page', 1) - 1) * 50);
    }

    public function getParent($id)
    {

        $model = DB::table('m_role_menus as a')
            ->join('roles as r', 'a.role_id', 'r.id')
            ->join('m_menu_roles as m', 'a.menu_id', 'm.id')
            ->select('a.*', 'm.*')
            ->where('a.role_id', $id)
            ->get();
        $message = 'Success';
        return response()->json(['message' => $message, 'model' => $model], 200);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'role_id'     => 'required',
            'menu_id'      => 'required',
            'menu_type'     => 'required',
            'sort'         => 'required',
            'parent_id'  => 'required',
            'isActive'      => 'required'
        ]);

        $model = MRoleMenu::create($request->all());
        $message = 'Successfuly created!';

        return response()->json(['message' => $message, 'model' => $model], 200);
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
        $model = MRoleMenu::findOrFail($id);
        $parent = DB::table('m_role_menus as a')
            ->join('m_menu_roles as m', 'a.menu_id', 'm.id')
            ->where('a.role_id', $model->role_id)
            ->get();

        return response()->json(['model' => $model, 'parent' => $parent], 200);
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
        $this->validate($request, [
            'role_id'   => 'required',
            'menu_id'   => 'required',
            'menu_type' => 'required',
            'sort'      => 'required',
            'parent_id' => 'required',
            'isActive'  => 'required'
        ]);

        $model = MRoleMenu::findOrFail($id);
        $model->update($request->all());

        $user_role  = Role::find($model->role_id);
        $menu       = MMenuRoles::find($model->menu_id);

        $parent = MMenuRoles::find($model->parent_id);

        if($parent === null){
            $parent = '<span class="text-red text-bold">-</span>';
        }else{
            $parent = $parent->title;
        }

        $message = 'Successfully updated';

        return response()->json([
            'message'   => $message,
            'model'     => $model,
            'user_role' => $user_role,
            'menu'      => $menu,
            'parent'    => $parent
        ], 200);

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
        $ids = explode(",",$id);
        $model = MRoleMenu::whereIn('id',$ids)->get();
        $message = '';

        if($model->count() > 0){

            MRoleMenu::destroy(collect($ids));

            $message = 'Successfully deleted';
        }else{
            $message = 'Not Found';
        }

        return response()->json( $message, 200);
    }
}
