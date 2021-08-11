<?php

namespace App\Http\Controllers;

use App\MWorkUsers;
use App\UnDistricts;
use Illuminate\Http\Request;
use App\Department;
use Illuminate\Support\Facades\Input;


class DepartmentController extends Controller
{

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

}
