<?php

namespace App\Http\Controllers;

use App\MWorkUsers;
use App\RoleDepartments;
use App\SDepartments;
use App\UnDistricts;
use Illuminate\Http\Request;
use App\Department;
use Illuminate\Support\Facades\Input;


class DepartmentController extends Controller
{

    public function sDepartments(Request $request)
    {
        //
        $search = SDepartments::orderBy('code', 'ASC');

        $query = Input::get ( 'query' );

        if($query) {
            $search->where('code', 'LIKE', '%'.$query.'%');
            $search->orWhere('local_code', 'LIKE', '%'.$query.'%');
            $search->orWhere('title', 'LIKE', '%'.$query.'%');
        }

        $models = $search->paginate(25);

        $models->appends ( array (
            'query' => Input::get ( 'query' )
        ) );

        return view('madmin.departments.s-departments',
            compact('models','query'))
            ->withDetails ( $models )->withQuery ( $query );

    }

    public function updateSDepartments()
    {

        $array = array('type' => 'sdep_upd');

        $oraDep =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraDep) {

            $oraDepDecode = json_decode($oraDep, true);

            foreach ($oraDepDecode as $value) {

                $depart_info = SDepartments::where('code', '=', $value['code'])->first();

                if ($depart_info) {

                    $depart_info->update([
                        'title' => $value['department_name'],
                        'title_ru' => $value['department_name'],
                        'code' => $value['code'],
                        'isActive' => 'A'
                    ]);

                } else {

                    $create_depart = new SDepartments();
                    $create_depart->title = $value['department_name'];
                    $create_depart->title_ru = $value['department_name'];
                    $create_depart->code = $value['code'];
                    $create_depart->isActive = 'A';
                    $create_depart->save();

                }

            }

        }

        return back()->with('success', 'Departament va bo`linmalar IABS tizimidan muvaffaqiyatli yangilandi');

    }

    public function getSDepartment($id)
    {
        //
        $model = SDepartments::find($id);

        return response()->json($model);
    }

    public function editSDepartment(Request $request)
    {
        $row_id = $request->model_id;

        $model = SDepartments::find($row_id);

        $model->update([
            'title' => $request->title,
            'local_code' => $request->local_code,
            'isActive' => $request->isActive
        ]);

        return response()->json($model);
    }

    public function index(Request $request)
    {
        //
        $search = Department::orderBy('branch_code', 'ASC');

        $query = Input::get ( 'query' );

        if($query) {
            $search->where('branch_code', 'LIKE', '%'.$query.'%');
            $search->orWhere('local_code', 'LIKE', '%'.$query.'%');
            $search->orWhere('title', 'LIKE', '%'.$query.'%');
        }

        $models = $search->paginate(20);

        $models->appends ( array (
            'query' => Input::get ( 'query' )
        ) );

        return view('madmin.departments.index',
            compact('models','query'))
            ->withDetails ( $models )->withQuery ( $query );

    }

    public function create()
    {

        $filial = Department::where('parent_id', 0)
            ->where('status', 1)
            ->orderBy('id', 'ASC')->get();

        return view('madmin.departments.create',compact('filial'));

    }

    public function userDepartment(Request $request){

        $id = $request->input('id');

        $model = Department::where('branch_code', $id)->where('status', 1)->get();

        return response()->json(array('success' => true, 'msg' => $model, 'branch' => $id));
    }

    public function getDepartment(Request $request){

        $branch = $request->input('branch_code');

        $depart_id = Department::select('id')
            ->where('branch_code', '=', $branch)
            ->where('parent_id', 0)
            ->first();

        $models = Department::where('parent_id', $depart_id->id)->where('status', 1)->get();

        return response()->json(array(
            'success' => true,
            'models' => $models,
            'branch' => $branch,
            'depart_id' => $depart_id->id
        ));
    }

    public function subDepartment(Request $request){

        $depart_id = $request->input('depart_id');

        $subDepartment = Department::where('parent_id', $depart_id)->get();

        return response()->json(array(
            'success' => true,
            'subDepartment' => $subDepartment,
            'depart_id' => $depart_id));
    }

    public function getDistricts(Request $request){

        $region_code = $request->input('region_code');

        $districts = UnDistricts::where('region_code', $region_code)->where('status', 1)->get();

        return response()->json($districts);
    }

    public function getRegDistricts(Request $request){

        $region_code = $request->input('reg_region_code');

        $districts = UnDistricts::where('region_code', $region_code)->where('status', 1)->get();

        return response()->json($districts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'title_ru' => 'required',
            'branch_code' => 'required',
            'local_code' => 'required',
        ]);

        $input = $request->all();

        $input['parent_id'] = empty($input['parent_id']) ? 0 : $input['parent_id'];

        $d = Department::where('id',$request['parent_id'])->first();

        // If a new created department is in 'Bosh Bank' and is a department
        if($request['branch_code'] == '09011' && $d->id == 1){
            $lastId = Department::orderBy('id', 'DESC')->first();
            $request['depart_id'] = $lastId->id + 1;
        }
        else{
            $request['depart_id'] = $d->depart_id;
        }

        Department::create($request->all());

        return back()->with('success', 'Yangi yozuv qo`shildi');
    }

    public function show(Department $department)
    {

        //print_r($department->childs); die;

        return view('madmin.departments.show',compact('department'));
    }

    public function view(Department $department)
    {

        return view('madmin.departments.show',compact('department'));
    }

    public function edit(Department $department)
    {

        $filials = Department::where('parent_id', 0)->where('status', 1)->orderBy('id','ASC')->get();

        $departments = Department::where('status', 1)->where('parent_id', 0)->get();

        return view('madmin.departments.edit',compact('filials', 'department','departments'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'title' => 'required',
            'title_ru' => 'required',
            'local_code' => 'required',
        ]);

        $d = Department::where('id',$request['parent_id'])->first();

        if($request['branch_code'] == '09011' && $d->id == 1){
            $request['depart_id'] = $department->id;
        }
        else{
            $request['depart_id'] = $d->depart_id;
        }

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success','Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $depart_id = $department->id;

        $isCheck = MWorkUsers::where('depart_id', $depart_id)->first();

        if ($isCheck) {
            $message = 'Ushbu Yozuvni o`chirish mumkin emas!!!';
        } else {
            $message = 'Ushbu Yozuv o`chirildi';
            $department->delete();

        }

        return back()->with('success',$message);
    }


    /*    public function filials(Request $request)
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

            return view('madmin.departments.filial',
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
        }*/

    /*public function updateRoleDepartment($branch_code)
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

    }*/
    /*public function updateDepartment($branch_code)
    {

        $array = array('branch_code' => $branch_code, 'type' => 'dep_upd');

        $oraDep =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraDep) {

            $oraDepDecode = json_decode($oraDep, true);
            //print_r($oraDepDecode); die;

            $department = Department::where('branch_code', '=', $branch_code)->where('parent_id', 0)->first();

            foreach ($oraDepDecode as $value) {

                $depart_info = Department::where('branch_code', '=', $branch_code)->where('ora_code', '=', $value['code'])->where('ora_condition', '=', 'A')->first();

                //print_r($depart_info); die;

                if ($depart_info) {

                    $depart_info->update([
                        'depart_id' => $depart_info->depart_id,
                        'title' => $value['department_name'],
                        'title_ru' => $value['department_name'],
                        'branch_code' => $value['filial'],
                        'local_code' => $value['filial'],
                        'parent_id' => $depart_info->parent_id,
                        'ora_parent_code' => $value['parent_code'],
                        'ora_code' => $value['code'],
                        'ora_condition' => $value['condition'],
                        'order_by' => $value['order_by'],
                        'status' => 1,
                    ]);

                } else {

                    $create_depart = new Department();
                    $create_depart->depart_id = $department->id;
                    $create_depart->title = $value['department_name'];
                    $create_depart->title_ru = $value['department_name'];
                    $create_depart->branch_code = $value['filial'];
                    $create_depart->local_code = $value['filial'];
                    $create_depart->parent_id = $department->id;
                    $create_depart->ora_parent_code = $value['parent_code'];
                    $create_depart->ora_code = $value['code'];
                    $create_depart->ora_condition = $value['condition'];
                    $create_depart->order_by = $value['order_by'];
                    $create_depart->status = 1;
                    $create_depart->save();

                }

            }

            $department_status = Department::where('branch_code', '=', $branch_code)->where('parent_id', '!=', 0)->where('ora_code', null)->get();
            foreach ($department_status as $status) {
                $status->update([
                    'ora_condition' => 'P',
                    'status' => 0,
                ]);

                //LOCAL UPDATE PARENT_ID
                $department = Department::where('branch_code', '=', $branch_code)->where('ora_parent_code', '=', $status['ora_code'])->where('parent_id', '!=', 0)->get();
                if ($department)
                {
                    foreach ($department as $dep) {
                        $department_parent = Department::where('branch_code', '=', $branch_code)->where('ora_code', '=', $dep->ora_parent_code)->first();
                        $dep->update([
                            'parent_id' => $department_parent->id
                        ]);
                    }
                }

            }


        }

        return back()->with('success', 'Departament va bo`linmalar IABS tizimidan muvaffaqiyatli yangilandi');

    }*/

}
