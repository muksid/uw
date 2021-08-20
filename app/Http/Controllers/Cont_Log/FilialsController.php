<?php

namespace App\Http\Controllers;

use App\Filials;
use App\RoleDepartments;
use App\SDepartments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FilialsController extends Controller
{
    //
    public function index(Request $request)
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

    public function viewFilial(RoleDepartments $roleDepartments, $code)
    {
        //
        $filial = Filials::where('filial_code', '=', $code)->firstOrFail();

        $models = RoleDepartments::where('filial_code', '=', $code)
            ->where('isActive', '=', 'A')
            ->where('parent_code','=', '000000')
            ->where('parent_code','!=', null)
            ->orderBy('order_by', 'ASC')->get();

        return view('madmin.departments.view-filial', compact('filial','models', 'roleDepartments'));

    }

    public function updateFilial()
    {

        $array = array('type' => 'filial_upd');

        $oraDep =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraDep) {

            $oraDepDecode = json_decode($oraDep, true);

            foreach ($oraDepDecode as $value) {

                $depart_info = Filials::where('filial_code', '=', $value['code'])->where('isActive', '=', 'A')->first();

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

    public function getFilial($id, $type)
    {
        //
        if ($type == 'f'){

            $model = Filials::find($id);

        } elseif ($type == 'd') {

            $model = SDepartments::find($id);

        }

        return response()->json($model);
    }

    public function filialUpdate(Request $request)
    {
        $row_id = $request->model_id;

        $type = $request->type;

        if ($type == 'f') {

            $model = Filials::find($row_id);

            $model->update([
                'title' => $request->title,
                'isActive' => $request->isActive
            ]);

        } elseif ($type == 'd') {

            $model = SDepartments::find($row_id);

            $model->update([
                'title' => $request->title,
                'local_code' => $request->local_code,
                'isActive' => $request->isActive
            ]);

        }

        return response()->json($model);
    }
}
