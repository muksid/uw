<?php

namespace App\Http\Controllers;

use App\Department;
use App\MWorkUsers;
use App\UnDistricts;
use App\UnRegions;
use App\UwClientComments;
use App\UwClientFiles;
use App\UwClientGuars;
use App\UwClients;
use App\UwClientDebtors;
use App\UwGuarType;
use App\UwLoanTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UwCreateClientsController extends Controller
{

    public function index(Request $request) {}

    public function getLoanType(Request $request)
    {
        //
        if ($request->ajax())
        {
            $output="";

            $depart = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();

            if ($depart->branch_code == '09011'){
                $models = UwLoanTypes::where('credit_type', $request->credit_type)
                    ->where('short_code', '!=', 'J')
                    ->where('isActive', 1)
                    ->orderBy('id', 'DESC')
                    ->get();

            } else {
                $models = UwLoanTypes::whereHas('banks',
                    function ($query) use ($depart) {
                        $query->where('branch_code', $depart->branch_code);
                        $query->where('isActive', 1);
                    })
                    ->where('credit_type', $request->credit_type)
                    ->where('short_code', '!=', 'J')
                    ->where('isActive', 1)
                    ->orderBy('id', 'DESC')
                    ->get();
            }

            if ($models)
            {
                $output .='<tr>'.
                    '<th>'.'ID'.'</th>'.
                    '<th>'.'Kredit nomi'.'</th>'.
                    '<th>'.'Foiz %'.'</th>'.
                    '<th>'.'Kredit Davri'.'</th>'.
                    '<th>'.'Imtiy davr'.'</th>'.
                    '<th>'.'Valyuta'.'</th>'.
                    '<th>'.'Qarz yuki %'.'</th>'.
                    '<tr>';
                foreach ($models as $key => $values) {
                    $key = $key+1;
                    $output .='<tr class="clickable-row tr-cursor" data-href="'.route("phy.create.step.one",["id" => $values->id]).'">'.
                        '<td>'.$key++.'</td>'.
                        '<td><a href="'.route("phy.create.step.one",["id" => $values->id]).'">'.$values->title.'</a></td>'.
                        '<td class="text-green">'.$values->procent.' %</td>'.
                        '<td>'.$values->credit_duration.' oy</td>'.
                        '<td>'.$values->credit_exemtion.' oy</td>'.
                        '<td>'.$values->currency.'</td>'.
                        '<td class="text-maroon">'.$values->dept_procent.' %</td>'.
                        '</tr>';
                }
            }
            return $output;
        }

    }

    public function create()
    {
        //
        function ftpExists(){

            $ftp_server = "172.16.1.233";
            $ftp_user = "Muksid";
            $ftp_pass = "TuR0N09011!@#$";

            $conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");

            if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
                return 1;
            } else {
                return 0;
            }
        }

        $depart = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->firstOrFail();

        if ($depart->branch_code == '09011'){
            $models = UwLoanTypes::where('short_code', '=', 'M')
                ->where('isActive', 1)
                ->orderBy('id', 'DESC')
                ->get();

        } else {
            $models = UwLoanTypes::with('banks')
                ->whereHas('banks',
                    function ($query) use ($depart) {
                        $query->where('depart_id', $depart->gen_dep_id);
                        $query->where('isActive', 1);
                    })
                ->where('short_code', '=', 'M')
                ->where('isActive', 1)
                ->orderBy('id', 'DESC')
                ->get();
        }

        $loan_models = UwLoanTypes::where('short_code', '!=', 'J')->get()->unique('credit_type');

        return view('phy.ins.create',compact('models', 'loan_models'));
    }

    public function createStepOne(Request $request, $id)
    {
        $loan = UwLoanTypes::find($id);

        $model = $request->session()->get('model');

        $regions = UnRegions::orderBy('code', 'ASC')->get();

        $districts = UnDistricts::orderBy('code', 'ASC')->get();

        $blade = mb_strtolower($loan->short_code);

        return view('phy.ins.create-i-step-one',compact('model','regions', 'districts', 'loan'));
    }

    public function postCreateStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'family_name' => 'required',
            'patronymic' => 'required',
            'document_region' => 'required',
            'document_district' => 'required',
            'registration_region' => 'required',
            'registration_district' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',
            'document_type' => 'required',
            'document_serial' => 'required',
            'document_number' => 'required',
            'document_date' => 'required',
            'pin' => 'required',
            //'inn' => 'required',
            'is_inps' => 'required',
            'registration_address' => 'required',
            'live_number' => 'required',
            'phone' => 'required',
            'job_address' => 'required',
        ]);

        $loan_type = $request->input('loan_type');

        if(empty($request->session()->get('model'))){
            $model = new UwClients();
            $model->fill($validatedData);
            $request->session()->put('model', $model);
        }else{
            $model = $request->session()->get('model');
            $model->fill($validatedData);
            $request->session()->put('model', $model);
        }

        return redirect()->route('phy.create.step.two', ['id' => $loan_type]);
    }

    public function createStepTwo(Request $request, $id)
    {
        $loan = UwLoanTypes::find($id);

        $model = $request->session()->get('model');

        $blade = mb_strtolower($loan->short_code);

        return view('phy.ins.create-i-step-two',compact('model', 'id', 'loan'));
    }

    public function postCreateStepTwo(Request $request)
    {
        $validatedData = $request->validate([
            'summa' => 'required',
        ]);

        $loan_type = $request->input('loan_type');

        $model = $request->session()->get('model');
        $model->fill($validatedData);
        $request->session()->put('model', $model);

        return redirect()->route('phy.create.step.three', ['id' => $loan_type]);
    }

    public function createStepThree(Request $request, $id)
    {
        $loan = UwLoanTypes::find($id);

        $model = $request->session()->get('model');

        $blade = mb_strtolower($loan->short_code);

        return view('phy.ins.create-i-step-three',compact('model', 'id', 'loan'));
    }

    public function postCreateStepThree(Request $request)
    {
        //
        $currentWorkUser = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();
        if (!$currentWorkUser){
            return back()->with(
                [
                    'status' => 'warning',
                    'message' => 'Inspektor passive holatda!!! (ip:247)'
                ]);
        }
        $model = $request->session()->get('model');

        $inn = preg_replace('/[^A-Za-z0-9]/', '', $model->inn);
        $checkModel = UwClients::where(
            function ($query) use ($model, $inn) {
                $query->orWhere('pin', $model->pin)
                    //->orWhere('inn', $inn)
                    ->orWhere(DB::raw("CONCAT(`document_serial`,`document_number`)"), $model->document_serial.$model->document_serial);
            })
            ->where(function ($status){
                $status->where('status', '!=', -1);
            })
            ->get();

        $phone = preg_replace('/[^A-Za-z0-9]/', '', $model->phone);
        $str_summa = str_replace(',', '.', $model->summa);
        $summa = str_replace(' ', '', $str_summa);

        $branchCode = $currentWorkUser->branch_code;
        //$localCode = Department::find($currentWorkUser->depart_id);

        $lastModelId = UwClients::where('branch_code', '=', $branchCode)->latest()->first();

        $loanType = UwLoanTypes::find($request->loan_type);

        $claim_number = $lastModelId->claim_number + 1;
        $claim_id = '1'.$branchCode.$claim_number;
        $model->work_user_id = $currentWorkUser->id;
        $model->branch_code = $branchCode;
        $model->local_code = $currentWorkUser->local_code;
        $model->claim_id = $claim_id;
        $model->claim_date = today();
        $model->claim_number = $claim_number;
        $model->agreement_number = $claim_number;
        $model->agreement_date = today();
        $model->client_type = "08";
        $model->resident = 1;
        $model->nibbd = "";
        $model->live_address = $model->registration_address;
        $model->inn = $inn;
        $model->phone = $phone;
        $model->katm_sir = "";
        $model->loan_type_id = $loanType->id;
        $model->credit_type = $loanType->credit_type;
        $model->summa = $summa;
        $model->status = 1;

        if ($checkModel->count() > 0){

            return back()->with(
                [
                    'status' => 'warning',
                    'message' => 'Mijoz tizimda mavjud Anderrayterga murojaat qiling!!! (ip:247,268,246)',
                    'data' => $checkModel,
                ]);
        }

        $model->save();

        $request->session()->forget('model');

        return redirect()->route('phy.create.step.result', ['id' => $model->id])->with(
            [
                'status' => 'info',
                'message' => 'Mijoz muvaffaqiyatli tizimga qo`shildi',
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     */
    public function createStepResult($id)
    {
        //
        $model = UwClients::find($id);

        $debtors = UwClientDebtors::where('uw_clients_id', $model->id)->where('isActive', 1)->get();

        $modelComments = UwClientComments::where('uw_clients_id', $id)->get();

        $regions = UnRegions::where('status', 1)->get();

        $districts = UnDistricts::where('status', 1)->get();


        $sch_type_d = 'checked';
        $sch_type_a = '';
        if ($model->sch_type == 2){
            $sch_type_d = '';
            $sch_type_a = 'checked';
        }

        return view('phy.ins.create-i-step-result',
            compact('model', 'debtors','modelComments', 'regions', 'districts', 'sch_type_d', 'sch_type_a'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientGuars($id)
    {
        //
        if(request()->ajax())
        {
            $model = UwClients::find($id);
            $disabled = '';
            if ($model->status == 2 || $model->status == 3){
                $disabled = 'btn disabled';
            }

            return datatables()->of(UwClientGuars::where('uw_clients_id', $id)->get())
                ->addColumn('action', function($data) use ($disabled) {

                    $button ='<a href="javascript:void(0)" data-id="'.$data->id.'" class="edit edit-guar '.$disabled.'">
                                    <span class="glyphicon glyphicon-pencil"></span>
                              </a>';
                    $button .= '&nbsp;&nbsp;';

                    $button .= ' | <a href="javascript:void(0);" id="delete-guar" data-id="'.$data->id.'" class="delete text-maroon  '.$disabled.'">
                                    <span class="glyphicon glyphicon-trash"></span>
                                 </a>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createClientGuar(Request $request)
    {
        //
        $id = $request->model_id;
        $postId = $request->post_id;

        $model = UwClients::find($id);

        $post = UwClientGuars::updateOrCreate(['id' => $postId],
            [
                'uw_clients_id' => $model->id,
                'claim_id' => $model->claim_id,
                'guar_type' => $request->guar_type,
                'title' => $request->title,
                //'address' => $request->address,
                //'guar_owner' => $request->guar_owner,
                'guar_sum' => $request->guar_sum
            ]);

        return response()->json(
            [
                'success'=>'Post successfully added',
                'data'=> $post,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editClientGuar($id)
    {
        //
        $model  = UwClientGuars::find($id);

        $guarTypes = UwGuarType::where('isActive', 1)->get();

        return response()->json(['model' => $model, 'guarTypes' => $guarTypes]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteClientGuar($id)
    {
        //
        $checkClient = UwClientGuars::find($id);

        if($checkClient){

            $checkClient->delete();

            return response()->json(
                [
                    'message'=>'Client deleted',
                    'code'=>200
                ]);
        }

        return response()->json(
            [
                'message'=>'Check Client not deleted!',
                'code'=>201
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientFiles($id)
    {
        //
        if(request()->ajax())
        {
            $model = UwClients::find($id);
            $disabled = '';
            if ($model->status == 2 || $model->status == 3){
                $disabled = 'btn disabled';
            }

            return datatables()->of(UwClientFiles::where('uw_client_id', $id)->get())
                ->addColumn('view', function($data){
                    $button  = '
                    <a href="'.url('/phy/client/download-file', ['file' => $data->id]).'" id="download-file" data-toggle="tooltip" data-original-title="Download" data-id="'.$data->id.'" class="text-primary">
 <span class="glyphicon glyphicon-download-alt"></span></a>';
                    return $button;
                })
                ->addColumn('trash', function($data) use($disabled){
                    $button1  = '
                    <a href="javascript:void(0);" id="delete-file" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete text-maroon  '.$disabled.'">
<span class="glyphicon glyphicon-trash"></span></a>';
                    return $button1;
                })
                ->rawColumns(['view', 'trash'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createClientFile(Request $request)
    {
        //
        $file = $request->file('model_file');

        $model_id = $request->model_file_id;

        $modelFile = new UwClientFiles();

        $today = Carbon::today();
        $year = $today->year;
        $month = $today->month;
        $day = $today->day;
        $path = 'uw/phy/files/'.$year.'/'.$month.'/'.$day.'/';

        $modelFile->uw_client_id = $model_id;

        $modelFile->file_path = $path;

        $modelFile->file_hash = $model_id . '_' . Auth::id() . '_' . date('dmYHis') . uniqid() . '.'
            . $file->getClientOriginalExtension();

        $modelFile->file_size = $file->getSize();

        //$file->move(public_path() . '/uwFiles/', $modelFile->file_hash);

        Storage::disk('ftp_nas')->put($path.$modelFile->file_hash, file_get_contents($file->getRealPath()));

        $modelFile->file_name = $file->getClientOriginalName();

        $modelFile->file_extension = $file->getClientOriginalExtension();

        $modelFile->save();

        return response()->json(array(
                'success' => true,
                'message'   => 'File Successfully upload',
                'class_name'  => 'alert-success'
            )
        );

    }

    public function deleteClientFile($id)
    {
        //
        $model = UwClientFiles::find($id);

        if (Storage::disk('ftp_nas')->exists($model->file_path.$model->file_hash)){

            Storage::disk('ftp_nas')->delete($model->file_path.$model->file_hash);
        }

        $model->delete();

        return response()->json(array(
                'success' => true,
                'message' => 'File Successfully deleted'
            )
        );
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
    }
}
