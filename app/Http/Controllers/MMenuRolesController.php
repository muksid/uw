<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MMenuRoles;
use App\MCoreMenu;
use App\MRoleMenu;

class MMenuRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $models = MMenuRoles::orderBy('created_at', 'DESC')->paginate(25);

        $core_menus = MCoreMenu::orderBy('created_at', 'DESC')->get();
        @include('count_message.php');

        return view('control.menus.m-menu-roles', compact('models','core_menus'))->with('i', (request()->input('page', 1) - 1) * 50);
    }


    public function search(Request $request)
    {
        # code...
        $input = $request->input('input');
        $core_menus = MCoreMenu::orderBy('created_at', 'DESC')->get();

        $models = MMenuRoles::where('title', 'like', '%'.$input.'%')
            ->orWhere('url_path', 'like', '%'.$input.'%')
            ->orWhere('lang_code', 'like', '%'.$input.'%')
            ->orWhere('icon_code', 'like', '%'.$input.'%')
            ->orWhere('text_class', 'like', '%'.$input.'%')
            ->orWhereHas('coreMenu', function($query) use($input){
                $query->where('title','like', '%'.$input.'%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        $models->appends ( array ('input' => $input) );

        @include('count_message.php');

        return view('control.menus.m-menu-roles', compact('models','core_menus','input'))->with('i', (request()->input('page', 1) - 1) * 50);
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
            'title'     => 'required',
            'url_path'      => 'required',
            'lang_code'     => 'required',
            'count'         => 'required',
            'core_menu_id'  => 'required',
            'isActive'      => 'required'
        ]);

        $model = MMenuRoles::create($request->all());
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
        $model = MMenuRoles::findOrFail($id);

        return response()->json($model, 200);
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
            'title'     => 'required',
            'url_path'      => 'required',
            'lang_code'     => 'required',
            'count'         => 'required',
            'core_menu_id'  => 'required',
            'isActive'      => 'required'
        ]);

        $model = MMenuRoles::findOrFail($id);
        $model->update($request->all());
        if($model->core_menu_id != 0){
            $core_menu = MCoreMenu::where('id', $model->core_menu_id)->first();
            $core_menu_title = $core_menu->title;
        }else{
            $core_menu_title = '<span class="text-red text-bold">-</span>';
        }

        $message = 'Successfully updated';

        return response()->json(['message' => $message, 'model' => $model, 'core_menu_title' => $core_menu_title], 200);

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
        $model = MMenuRoles::whereIn('id',$ids)->get();
        $message = '';

        $role_menus = MRoleMenu::whereIn('menu_id', $ids)
            ->orWhereIn('parent_id', $ids)->get();

        if($role_menus->count() > 0){
            MRoleMenu::whereIn('menu_id', $ids)
                ->orWhereIn('parent_id', $ids)
                ->delete();
        }

        if($model->count() > 0){

            MMenuRoles::destroy(collect($ids));

            $message = 'Successfully deleted';
        }else{
            $message = 'Not Found';
        }

        return response()->json( $message, 200);
    }

}
