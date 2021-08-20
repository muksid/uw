<?php

namespace App\Http\Controllers;

use App\Filials;
use App\RoleDepartments;
use App\SDepartments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FilialController extends Controller
{

    public function index()
    {
        //
        $search = Filials::orderBy('code_level', 'ASC')->orderBy('filial_code');

        $query = Input::get ( 'query' );

        if($query) {
            $search->where('filial_code', 'LIKE', '%'.$query.'%');
            $search->orWhere('local_code', 'LIKE', '%'.$query.'%');
            $search->orWhere('title', 'LIKE', '%'.$query.'%');
        }

        $models = $search->paginate(25);

        $models->appends ( array (
            'query' => Input::get ( 'query' )
        ) );

        return view('madmin.filial.index',
            compact('models','query'))
            ->withDetails ( $models )->withQuery ( $query );

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
        print_r('11'); die;
    }

    public function show($id)
    {
        //
        $model = Filials::find($id);

        return response()->json($model);

    }

    public function edit(RoleDepartments $roleDepartments,$id)
    {
        //
        $filial = Filials::findOrFail($id);

        $models = RoleDepartments::where('filial_code', '=', $filial->filial_code)
            ->where('isActive', '=', 'A')
            ->where('parent_code','=', '000000')
            ->where('parent_code','!=', null)
            ->orderBy('order_by', 'ASC')->get();

        return view('madmin.filial.show', compact('filial','models', 'roleDepartments'));
    }

    public function update(Request $request, $id)
    {
        //
        $row_id = $request->model_id;

        $model = Filials::find($row_id);

        $model->update([
            'title' => $request->title,
            'isActive' => $request->isActive
        ]);

        return response()->json($model);
    }

    public function destroy($id)
    {
        //
    }

    public function getUpdateOra()
    {
        //
        $array = array('type' => 'filial_upd');

        $oraDep =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraDep) {

            $oraDepDecode = json_decode($oraDep, true);

            foreach ($oraDepDecode as $value) {

                $depart_info = Filials::where('filial_code', '=', $value['code'])->first();

                if ($depart_info) {

                    $depart_info->update([
                        'title' => $value['name'],
                        'title_ru' => $value['name'],
                        'code_header' => $value['code_header'],
                        'filial_code' => $value['code'],
                        'local_code' => $value['code_local'],
                        'code_level' => $value['code_level'],
                        'isActive' => $value['condition']
                    ]);

                } else {

                    $create_depart = new Filials();
                    $create_depart->title = $value['name'];
                    $create_depart->title_ru = $value['name'];
                    $create_depart->code_header = $value['code_header'];
                    $create_depart->filial_code = $value['code'];
                    $create_depart->local_code = $value['code_local'];
                    $create_depart->code_level = $value['code_level'];
                    $create_depart->isActive = $value['condition'];
                    $create_depart->save();

                }

            }

        }

        return back()->with('success', 'Filiallar IABS tizimidan muvaffaqiyatli yangilandi');

    }

    public function updateRoleDepartment($branch_code)
    {
        //

        $array = array('branch_code' => $branch_code, 'type' => 'role_dep_upd');

        $oraDep =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraDep) {

            $oraDepDecode = json_decode($oraDep, true);

            foreach ($oraDepDecode as $value) {

                $depart_info = RoleDepartments::where('filial_code', '=', $branch_code)->where('code', '=', $value['code'])->first();

                if ($depart_info) {

                    $depart_info->update([
                        'code' => $value['code'],
                        'parent_code' => $value['parent_code'],
                        'filial_code' => $value['filial'],
                        'lev' => $value['lev'],
                        'order_by' => $value['order_by'],
                        'status' => $value['condition'],
                    ]);

                } else {

                    $create_depart = new RoleDepartments();
                    $create_depart->code = $value['code'];
                    $create_depart->parent_code = $value['parent_code'];
                    $create_depart->filial_code = $value['filial'];
                    $create_depart->lev = $value['lev'];
                    $create_depart->isActive = $value['condition'];
                    $create_depart->order_by = $value['order_by'];
                    $create_depart->save();

                }

            }

        }

        return back()->with('success', 'Departament va bo`linmalar IABS tizimidan muvaffaqiyatli yangilandi');

    }


}
