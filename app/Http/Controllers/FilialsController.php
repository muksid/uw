<?php

namespace App\Http\Controllers;

use App\Filials;
use App\Role;
use App\User;
use App\UwUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilialsController extends Controller
{
    //
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

            $models = Filials::orderBy('filial_code', 'ASC')->get();

            $parent = Filials::where('parent_id', 0)
                ->where('status', 1)
                ->orderBy('id', 'ASC')->get();
            return view('uw.filials.index', compact('models', 'parent'));

        } else {

            return redirect('/uw/home');
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $row_id = $request->model_id;

        $parent = 0;
        if ($request->parent_id > 0) {
            $parent = $request->parent_id;
        }

        $user = Filials::updateOrCreate(['id' => $row_id],
            [
                'title' => $request->title,
                'title_ru' => $request->title_ru,
                'filial_code' => $request->filial_code,
                'local_code' => $request->local_code,
                'parent_id' => $parent,
                'f_sort' => $request->f_sort,
                'status' => $request->status
            ]);

        return response()->json($user);
    }

    public function edit($id)
    {
        //
        $user = Filials::find($id);

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Filials::find($id);

        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Filials::find($id)->delete();

        return response()->json(['success'=>'Filial Deleted successfully']);
    }
}
