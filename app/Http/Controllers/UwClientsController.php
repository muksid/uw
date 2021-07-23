<?php

namespace App\Http\Controllers;

use App\Department;
use App\MWorkUsers;
use App\UnDistricts;
use App\UnRegions;
use App\User;
use App\UwClientComments;
use App\UwClientFiles;
use App\UwClients;
use App\UwInpsClients;
use App\UwKatmClients;
use App\UwLoanTypes;
use App\UwUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Response;

class UwClientsController extends Controller
{
    /**
     * loan type 21 kredit bes otkrtie lini
     * loan type 24 ipoteka kredit
     * loan type 25 mikrokredit
     * loan type 26 lizing
     * loan type 28 Faktoring
     * loan type 30 potreblichiski kredit
     * loan type 32 mikrozaem
     * loan type 33 Кредиты, выданные по инициативе банка
     * loan type 34 Avtokredit
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        //
        UwUsers::where('user_id', Auth::id())->where('status', 1)->firstorFail();

        return view('uw.uw-clients.home');
    }

    public function index()
    {
        //
        UwUsers::where('user_id', Auth::id())->where('status', 1)->firstorFail();

        $models = UwClients::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->get();

        return view('uw.uw-clients.index', compact('models'));
    }

    public function CsIndex($status)
    {
        //
        $currentWorkUser = MWorkUsers::with('department')->where('user_id', Auth::id())->where('isActive', 'A')->first();

        $models = UwClients::where('id', 0)->get();
        if ($currentWorkUser){
            $user = MWorkUsers::where('user_id', Auth::id())->get()->pluck('id');
            $workUserIds = $user->toArray();

            $local_code_id = $currentWorkUser->department->id;

            $local_code = Department::find($local_code_id);

            $models = UwClients::where('branch_code', $local_code->branch_code)
                ->where('local_code', $local_code->local_code)
                ->whereIn('work_user_id', $workUserIds)
                ->where('status', $status)
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('uw.uw-clients.index', compact('models'));
    }

    public function riskAdminIndex($status)
    {
        //
        //UwUsers::where('user_id', Auth::id())->where('status', 1)->firstorFail();

        $search = UwClients::where('status', $status);

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $d = Input::get ( 'd' );

        if($u) {
            $search->where('work_user_id', $u);
        }

        if($t) {
            $search->where(function ($query) use ($t, $status) {

                $query->orWhere('branch_code', 'LIKE', '%' . $t . '%');
                $query->orWhere('iabs_num', 'LIKE', '%' . $t . '%');
                $query->orWhere('claim_id', 'LIKE', '%' . $t . '%');
                $query->orWhereRaw("CONCAT(`family_name`, ' ', `name`,' ', `patronymic`) LIKE ?", ['%'.$t.'%']);
                $query->orWhere('inn', 'LIKE', '%' . $t . '%');
                $query->orWhere('summa', 'LIKE', '%' . $t . '%');

            });
        }

        if($d) {
            $search->where('created_at', 'LIKE', '%'.$d.'%');
        }

        $models = $search->orderBy('id', 'DESC')
            ->paginate(25);

        $models->appends ( array (
            'u' => Input::get ( 'u' ),
            't' => Input::get ( 't' ),
            'd' => Input::get ( 'd' )
        ) );

        $users = User::select('id')->where('status', 1)->where('isUw', 1)->get();

        $searchUser = MWorkUsers::find($u);

        return view('uw.uw-clients.risk-admin-index',compact('models','u','t','d','users','searchUser','status'));
    }

    public function allClients()
    {
        //
        $search = UwClients::orderBy('id', 'DESC');

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $d = Input::get ( 'd' );

        if($u) {
            $search->where('work_user_id', $u);
        }

        if($t) {
            $search->where(function ($query) use ($t) {
                $query->orWhere('branch_code', 'LIKE', '%' . $t . '%');
                $query->orWhere('iabs_num', 'LIKE', '%' . $t . '%');
                $query->orWhere('claim_id', 'LIKE', '%' . $t . '%');
                $query->orWhereRaw("CONCAT(`family_name`, ' ', `name`,' ', `patronymic`) LIKE ?", ['%'.$t.'%']);
                $query->orWhere('inn', 'LIKE', '%' . $t . '%');
                $query->orWhere('summa', 'LIKE', '%' . $t . '%');

            });
        }

        if($d) {
            $search->where('created_at', 'LIKE', '%'.$d.'%');
        }

        $models = $search->paginate(25);

        $models->appends ( array (
            'u' => Input::get ( 'u' ),
            't' => Input::get ( 't' ),
            'd' => Input::get ( 'd' )
        ) );

        $users = User::select('id')->where('status', 1)->where('isUw', 1)->get();

        $searchUser = MWorkUsers::find($u);

        return view('uw.uw-clients.super-admin-index',compact('models','u','t','d','users','searchUser'));

    }

    public function riskAdminView($id,$claim_id)
    {
        //
        $model = UwClients::findOrFail($id);

        $modelComments = UwClientComments::where('uw_clients_id', $id)->get();

        $duplicateClients = UwClients::where('id', '!=', $model->id)
            ->where('inn', '=',$model->inn)
            ->orWhere(DB::raw("CONCAT(`document_serial`,`document_number`)"), '=',$model->document_serial.$model->document_serial)
            ->get();

        $sch_type_d = 'checked';
        $sch_type_a = '';
        if ($model->sch_type == 2){
            $sch_type_d = '';
            $sch_type_a = 'checked';
        }

        return view('uw.uw-clients.risk-admin-view', compact('model', 'modelComments', 'duplicateClients', 'sch_type_d', 'sch_type_a'));
    }

    public function superAdminView($id,$claim_id)
    {
        //
        UwUsers::where('user_id', Auth::id())->where('status', 1)->firstorFail();

        $model = UwClients::where('id', $id)->where('claim_id', $claim_id)->firstOrFail();

        $uw_user = UwUsers::where('user_id', Auth::id())->first();

        $costs = UwInpsClients::where('claim_id', $claim_id)->groupBy('claim_id')->sum('INCOME_SUMMA');

        $katm = UwKatmClients::where('claim_id', $claim_id)->first();

        $costs_m = UwInpsClients::where('claim_id', $claim_id)->groupBy('PERIOD')->get()->count();

        $katm_scoring = json_decode($katm['katm_score'], true);

        if ($katm && $costs_m){
            if ($katm->katm_summ == 1){
                $katm_sum = 0;
            } else {
                $katm_sum = $katm->katm_summ;
            }
            $summ_en = ($costs / $costs_m * 0.5) - $katm_sum;

            $loan_summ_en = $summ_en * $model->credit_duration /(+$model->credit_duration*($model->procent/100)/365*30+1);

            $scoring_ball = $katm_scoring['sc_ball'];
        }

        return view('uw.uw-clients.super-admin-view', compact('model', 'uw_user','costs','summ_en',
            'loan_summ_en','katm_sum','costs_m', 'scoring_ball', 'katm', 'katm_scoring'));
    }

    public function csAppSend(Request $request){

        $id = $request->input('uw_clients_id');

        $model = UwClients::findOrFail($id);

        $model->update(['status' => 2, 'iabs_num' => $request->iabs_num, 'sch_type' => $request->sch_type]);

        return response()->json(array('success' => true, 'msg' => 'send'));
    }

    public function riskAdminConfirm(Request $request){

        $id = $request->input('uw_clients_id');

        $model = UwClients::find($id);

        $model->update(['status' => 3]);

        // Risk admin comments
        $modelComment = new UwClientComments();
        $modelComment->uw_clients_id = $id;
        $modelComment->work_user_id = Auth::user()->currentWork->id??0;
        $modelComment->claim_id = $model->claim_id;
        $modelComment->title = 'Anderrayter tomonidan tasdiqlangan';
        $modelComment->comment_type = 3;
        $modelComment->process_type = 'CONF';
        $modelComment->save();

        return response()->json(array('success' => true, 'msg' => $id));
    }

    public function riskAdminCancel(Request $request){

        $id = $request->input('uw_clients_id');

        $descr = $request->input('descr');

        $model = UwClients::find($id);

        $model->update(['status' => 0]);

        // Risk admin comments
        $modelComment = new UwClientComments();
        $modelComment->uw_clients_id = $id;
        $modelComment->work_user_id = Auth::user()->currentWork->id??0;
        $modelComment->claim_id = $model->claim_id;
        $modelComment->title = $descr;
        $modelComment->comment_type = 1;
        $modelComment->process_type = 'CANC';
        $modelComment->save();

        return response()->json(array('success' => true));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        UwUsers::where('user_id', Auth::id())->where('status', 1)->firstorFail();

        $regions = UnRegions::orderBy('code', 'ASC')->get();
        $districts = UnDistricts::orderBy('code', 'ASC')->get();

        return view('uw.uw-clients.create', compact('regions', 'districts'));
    }

    public function getDistricts(Request $request){

        $region_code = $request->input('region_code');

        $districts = UnDistricts::where('region_code', $region_code)->where('status', 1)->get();

        return response()->json(array('success' => true, 'msg' => $districts));
    }

    public function getRegDistricts(Request $request){

        $region_code = $request->input('reg_region_code');

        $districts = UnDistricts::where('region_code', $region_code)->where('status', 1)->get();

        return response()->json(array('success' => true, 'msg' => $districts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function storeEdit(Request $request)
    {
        $row_id = $request->model_id;

        $document_date = date("Y-m-d", strtotime($request->document_date));

        $birth_date = date("Y-m-d", strtotime($request->birth_date));

        $model = UwClients::updateOrCreate(['id' => $row_id],
            [
                'inn' => $request->inn,
                'document_serial' => $request->document_serial,
                'document_number' => $request->document_number,
                'document_date' => $document_date,
                'birth_date' => $birth_date,
                'gender' => $request->gender,
                'family_name' => $request->family_name,
                'name' => $request->name,
                'patronymic' => $request->patronymic,
                'pin' => $request->pin,
                'summa' => $request->summa,
                'is_inps' => $request->is_inps,
                'registration_address' => $request->registration_address,
                'live_address' => $request->registration_address,
                'job_address' => $request->job_address,
                'iabs_num' => $request->iabs_num,
                'loan_type_id' => $request->loan_type_id,
            ]);

        return response()->json([
            'model' => $model,
            'credit_debt' => $model->katm->summa?? 0,
            'loan_name' => $model->loanType->title,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function riskEdit(Request $request)
    {
        $row_id = $request->model_id;

        // update model
        $model = UwClients::find($row_id);
        $model->update([
            'status' => $request->status,
            'reg_status' => $request->reg_status,
            'user_id' => $request->cs_user_id
        ]);

        // update katm
        if ($request->reg_katm == 0){
            $modelKatm = UwKatmClients::where('uw_clients_id', $row_id);
            $modelKatm->update(['status' => $request->reg_katm]);
        }

        // update inps
        if ($request->reg_inps == 0){
            $modelInps = UwInpsClients::where('uw_clients_id', $row_id);
            $modelInps->update(['status' =>$request->reg_inps]);
        }

        // create comment on update
        $comment = new UwClientComments();
        $comment->uw_clients_id = $model->id;
        $comment->claim_id = $model->claim_id;
        $comment->work_user_id = Auth::user()->currentWork->id??0;
        $comment->title = 'Client status updated';
        $comment->json_data = json_encode($request->all());
        $comment->comment_type = $request->status;
        $comment->process_type = 'UPD';
        $comment->save();

        return response()->json([
            'model' => $model,
            'sc_ball' => $model->katm->katm_sc_ball?? 0,
            'loan_name' => $model->loanType->title?? '',
        ]);
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
        $model = UwClients::find($id);

        $modelLoanType = UwLoanTypes::where('isActive', 1)->get();

        $csUsers = DB::table('users')
            ->join('m_personal_users', 'users.id', '=', 'm_personal_users.user_id')
            ->join('m_work_users', 'users.id', '=', 'm_work_users.user_id')
            ->join('departments', 'm_work_users.depart_id', '=', 'departments.id')
            ->select('m_work_users.id as work_user_id',
                DB::raw('CONCAT(m_personal_users.l_name," ", m_personal_users.f_name) AS full_name'),
                'm_work_users.branch_code as filial_code', 'departments.title as filial_name')
            ->get();

        return response()->json([
            'model' => $model,
            'modelLoanType' => $modelLoanType,
           // 'csUsers' => $csUsers,
        ]);

    }

    public function clientKatm($id,$claim_id)
    {
        //
        UwUsers::where('user_id', Auth::id())->where('status', 1)->firstorFail();

        $model = UwClients::where('id', $id)->where('claim_id', $claim_id)->firstOrFail();

        $uw_user = UwUsers::where('user_id', Auth::id())->first();

        $costs = UwInpsClients::where('claim_id', $claim_id)->groupBy('claim_id')->sum('INCOME_SUMMA');

        $costs_m = UwInpsClients::where('claim_id', $claim_id)->groupBy('PERIOD')->get()->count();

        $katm = UwKatmClients::where('claim_id', $claim_id)->first();

        $katm_scoring = json_decode($katm['katm_score'], true);

        if ($katm && $costs_m){
            if ($katm->katm_summ == 1){
                $katm_sum = 0;
            } else {
                $katm_sum = $katm->katm_summ;
            }
            $summ_en = ($costs / $costs_m * 0.5) - $katm_sum;

            $loan_summ_en = $summ_en * $model->credit_duration /(+$model->credit_duration*($model->procent/100)/365*30+1);

            $scoring_ball = $katm_scoring['sc_ball'];
        }

        return view('uw.uw-clients.katm', compact('model', 'uw_user','costs','summ_en',
            'loan_summ_en','katm_sum','costs_m', 'scoring_ball', 'katm', 'katm_scoring'));
    }

    public function postKatm(Request $request)
    {

        $model_id = $request->input('uw_clients_id');
        $claim_id = $request->input('claim_id');
        $uw_is_inps = $request->input('uw_is_inps');

        if ($request->is_inps == 1) {

            return $this->postInps($model_id, $claim_id);
        }

        $url = 'http://10.22.50.3:8001/katm-api/v1/credit/report';

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".Auth::user()->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$claim_id."",
                "pReportId" => 21,
                "pReportFormat" => 1
            ),
        );

        $postdata = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);

        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];
        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($code == '200'){
            if ($result == '05050'){
                $token = $data_decode['data']['token'];
                return $this->postKatm1($token, $model_id, $claim_id, $uw_is_inps);

            } else {
                return back()->with('error', $resultMessage);
            }

        } else {
            return back()->with('error', $resultMessage);
        }

    }

    public function postKatm1($token, $model_id, $claim_id, $uw_is_inps)
    {
        //
        $url1 = 'http://10.22.50.3:8001/katm-api/v1/credit/report/status';
        $data1 = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".Auth::user()->branch_code."",
                "pToken" => "".$token."",
                "pLegal" => 1,
                "pClaimId" => "".$claim_id."",
                "pReportId" => 21,
                "pReportFormat" => 1
            ),
        );

        $postdata1 = json_encode($data1);

        $ch1 = curl_init($url1);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postdata1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result1 = curl_exec($ch1);
        curl_close($ch1);

        $data_decode1 = json_decode($result1, true);
        $reportBase64 = $data_decode1['data']['reportBase64'];
        if (!$reportBase64){
            return $this->postKatm1($token, $model_id, $claim_id, $uw_is_inps);
        } else{

            $base64_decode = base64_decode($reportBase64);

            $models_data = json_decode($base64_decode, true);
            $md_array = $models_data['html']['body']['div'][1]['table'][1]['tbody']['tr'];
            $scoring_ball = $md_array[10]['td'][1]['div']['span'];
            $score_level_info = $md_array[12]['td'][1]['span'];
            $score_date = $md_array[13]['td'][1]['span'];
            $score_version = $md_array[14]['td'][1]['span'];

            $client_info_1 = $md_array[1]['td'][1]['span']; /*fio*/
            $client_info_2_text = $md_array[2]['td'][1]['span']; /*den roj*/
            $client_info_4 = $md_array[4]['td'][1]['span']; /*adres*/
            $client_info_5 = $md_array[5]['td'][1]['span']; /*pinfl*/
            $client_info_6 = $md_array[6]['td'][1]['span']; /*pinfl*/
            $client_info_8 = $md_array[8]['td'][1]['span']; /*pasport*/

            /**/
            $score_img = $md_array[10]['td'][1]['img']['src'];
            $file_name = $claim_id.'.php';
            $file_path = public_path().'/katm_files/'.$file_name;
            $score_img_file = fopen($file_path, "w") or die("Unable to open file!");
            fwrite($score_img_file, $score_img);
            fclose($score_img_file);

            /* -- tb 1 -- */
            $tb_1_1 = $md_array['19']['td'][2]['span'];
            $tb_1_2 = $md_array['19']['td'][3]['span'];
            $tb_1_3 = $md_array['19']['td'][4]['span'];
            $tb_1_4 = $md_array['19']['td'][5]['span'];

            /* -- tb 2 -- */
            $tb_2_1 = $md_array['20']['td'][2]['span'];
            $tb_2_2 = $md_array['20']['td'][3]['span'];
            $tb_2_3 = $md_array['20']['td'][4]['span'];
            $tb_2_4 = $md_array['20']['td'][5]['span'];

            /* -- tb 3 -- */
            $tb_3_1 = $md_array['21']['td'][2]['span'];
            $tb_3_2 = $md_array['21']['td'][3]['span'];
            $tb_3_3 = $md_array['21']['td'][4]['span'];
            $tb_3_4 = $md_array['21']['td'][5]['span'];

            /* -- tb 4 -- */
            $tb_4_1 = $md_array['22']['td'][2]['span'];
            $tb_4_2 = $md_array['22']['td'][3]['span'];
            $tb_4_3 = $md_array['22']['td'][4]['span'];
            $tb_4_4 = $md_array['22']['td'][5]['span'];

            /* -- tb 5 -- */
            $tb_5_1 = $md_array['23']['td'][2]['span'];
            $tb_5_2 = $md_array['23']['td'][3]['span'];
            $tb_5_3 = $md_array['23']['td'][4]['span'];
            $tb_5_4 = $md_array['23']['td'][5]['span'];

            /* -- tb 6 -- */
            $tb_6_1 = $md_array['24']['td'][2]['span'];
            $tb_6_2 = $md_array['24']['td'][3]['span'];
            $tb_6_3 = $md_array['24']['td'][4]['span'];
            $tb_6_4 = $md_array['24']['td'][5]['span'];

            /* -- tb 7 -- */
            $tb_7_1 = $md_array['25']['td'][2]['span'];
            $tb_7_2 = $md_array['25']['td'][3]['span'];
            $tb_7_3 = $md_array['25']['td'][4]['span'];
            $tb_7_4 = $md_array['25']['td'][5]['span'];

            /* -- tb 8 -- */
            $tb_8_1 = $md_array['26']['td'][2]['span'];
            $tb_8_2 = $md_array['26']['td'][3]['span'];
            $tb_8_3 = $md_array['26']['td'][4]['span'];
            $tb_8_4 = $md_array['26']['td'][5]['span'];

            /* -- tb 9 -- */
            $tb_9_1 = $md_array['27']['td'][2]['span'];
            $tb_9_2 = $md_array['27']['td'][3]['span'];
            $tb_9_3 = $md_array['27']['td'][4]['span'];
            $tb_9_4 = $md_array['27']['td'][5]['span'];

            /* -- tb 12 -- */
            if (count($md_array[30]['td'][2]['span'][0]) > 1) {
                # code...
                $tb_12_0 = $md_array[30]['td'][2]['span'][0]['span'];
            } else{
                $tb_12_0 = 0;
            }

            if (count($md_array[30]['td'][2]['span'][1]) > 1) {
                $tb_12_1 = $md_array[30]['td'][2]['span'][1]['span'];
            } else {
                $tb_12_1 = '';
            }

            if (count($md_array[30]['td'][2]['span'][2]) > 1) {
                $tb_12_2 = $md_array[30]['td'][2]['span'][2]['span'];
            } else {
                $tb_12_2 = '';
            }

            if (count($md_array[30]['td'][2]['span'][3]) > 1) {
                $tb_12_3 = $md_array[30]['td'][2]['span'][3]['span'];
            } else {
                $tb_12_3 = '';
            }

            if (count($md_array[30]['td'][2]['span'][4]) > 1) {
                $tb_12_4 = $md_array[30]['td'][2]['span'][4]['span'];
            } else {
                $tb_12_4 = '';
            }


            $arr_scoring = array(
                'sc_ball' => $scoring_ball,
                'sc_level_info' => $score_level_info,
                'score_date' => $score_date,
                'sc_version' => $score_version,
                'client_info_1' => $client_info_1,
                'client_info_2_text' => $client_info_2_text,
                'client_info_4' => $client_info_4,
                'client_info_5' => $client_info_5,
                'client_info_6' => $client_info_6,
                'client_info_8' => $client_info_8,
            );
            $arr_scoring_json = json_encode($arr_scoring);

            $arr_tb = array(
                'row_1' => array('open_total' => $tb_1_1, 'open_summ' => $tb_1_2, 'close_total' => $tb_1_3, 'close_summ' => $tb_1_4) ,
                'row_2' => array('open_total' => $tb_2_1, 'open_summ' => $tb_2_2, 'close_total' => $tb_2_3, 'close_summ' => $tb_2_4) ,
                'row_3' => array('open_total' => $tb_3_1, 'open_summ' => $tb_3_2, 'close_total' => $tb_3_3, 'close_summ' => $tb_3_4) ,
                'row_4' => array('open_total' => $tb_4_1, 'open_summ' => $tb_4_2, 'close_total' => $tb_4_3, 'close_summ' => $tb_4_4) ,
                'row_5' => array('open_total' => $tb_5_1, 'open_summ' => $tb_5_2, 'close_total' => $tb_5_3, 'close_summ' => $tb_5_4) ,
                'row_6' => array('open_total' => $tb_6_1, 'open_summ' => $tb_6_2, 'close_total' => $tb_6_3, 'close_summ' => $tb_6_4) ,
                'row_7' => array('open_total' => $tb_7_1, 'open_summ' => $tb_7_2, 'close_total' => $tb_7_3, 'close_summ' => $tb_7_4) ,
                'row_8' => array('open_total' => $tb_8_1, 'open_summ' => $tb_8_2, 'close_total' => $tb_8_3, 'close_summ' => $tb_8_4) ,
                'row_9' => array('open_total' => $tb_9_1, 'open_summ' => $tb_9_2, 'close_total' => $tb_9_3, 'close_summ' => $tb_9_4) ,
                'row_12' => array('agr_summ' => $tb_12_0, 'agr_date' => $tb_12_1, 'agr_comm2' => $tb_12_2,
                    'agr_comm3' => $tb_12_3, 'agr_comm4' => $tb_12_4));
            $arr_tb_json = json_encode($arr_tb);

            $summ = preg_replace('/[^0-9]/', '', $tb_12_0);

            UwKatmClients::updateOrCreate(['claim_id' => $claim_id],
                [
                    'uw_clients_id' => $model_id,
                    'claim_id' => $claim_id,
                    'katm_summ' => $summ,
                    'katm_sc_ball' => $scoring_ball,
                    'status' => 1,
                    'katm_score' => $arr_scoring_json,
                    'katm_tb' => $arr_tb_json
                ]);
            $model_sc_ball = UwKatmClients::where('claim_id', $claim_id)->first();

            if ($uw_is_inps == 1 && $model_sc_ball->katm_sc_ball > 199){

                return $this->postInps($model_id, $claim_id);

            } else {
                return back()->with('success', 'KATM ma`lumotlari muvaffaqiyatli saqlandi');
            }
        }
    }


    public function postInps($model_id, $claim_id)
    {

        $url = 'http://10.22.50.3:8001/katm-api/v1/credit/report';

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".Auth::user()->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$claim_id."",
                "pReportId" => 25,
                "pReportFormat" => 1
            ),
        );

        $postdata = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);

        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];
        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($code == '200'){
            if ($result == '05050'){
                $token1 = $data_decode['data']['token'];
                return $this->postInps1($token1, $model_id, $claim_id);

            } else {
                return back()->with('error', $resultMessage);
            }

        } else {

            return back()->with('error', 'Сведения о доходах по ИНПС не найдены');
        }

    }

    public function postInps1($token1, $model_id, $claim_id){
        $url1 = 'http://10.22.50.3:8001/katm-api/v1/credit/report/status';
        $data1 = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".Auth::user()->branch_code."",
                "pToken" => "".$token1."",
                "pLegal" => 1,
                "pClaimId" => "".$claim_id."",
                "pReportId" => 25,
                "pReportFormat" => 1
            ),
        );

        $postdata1 = json_encode($data1);

        $ch1 = curl_init($url1);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postdata1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result1 = curl_exec($ch1);
        curl_close($ch1);

        $data_decode1 = json_decode($result1, true);
        $result = $data_decode1['data']['result'];
        $reportBase64 = $data_decode1['data']['reportBase64'];
        if (!$reportBase64){
            return $this->postInps1($token1, $model_id, $claim_id);
        } else{

            $base64_decode = base64_decode($reportBase64);

            $models = json_decode($base64_decode, true);

            if ($models['report']['incomes']) {
                $old_inps = UwInpsClients::where('uw_clients_id', $model_id);
                $old_inps->delete();

                $models_array = $models['report']['incomes']['INCOME'];
                if (array_filter($models_array, 'is_array')) {
                    # code...
                    foreach ($models_array as $key => $value) {
                        # code...
                        $inps = new UwInpsClients();

                        $inps->uw_clients_id = $model_id;
                        $inps->claim_id = $claim_id;
                        $inps->ORG_INN = $value['ORG_INN'];
                        $inps->INCOME_SUMMA = $value['INCOME_SUMMA'];
                        $inps->NUM = $value['NUM'];
                        $inps->PERIOD = $value['PERIOD'];
                        $inps->ORGNAME = $value['ORGNAME'];
                        $inps->status = 1;
                        $inps->save();
                    }

                } else {
                    $inps = new UwInpsClients();

                    $inps->uw_clients_id = $model_id;
                    $inps->claim_id = $claim_id;
                    $inps->ORG_INN = $models_array['ORG_INN'];
                    $inps->INCOME_SUMMA = $models_array['INCOME_SUMMA'];
                    $inps->NUM = $models_array['NUM'];
                    $inps->PERIOD = $models_array['PERIOD'];
                    $inps->ORGNAME = $models_array['ORGNAME'];
                    $inps->status = 1;
                    $inps->save();
                }

                return back()->with('success', 'KATM va INPS ma`lumotlari muvaffaqiyatli saqlandi');

            } else{
                $old_inps = UwInpsClients::where('uw_clients_id', $model_id);
                $old_inps->delete();

                $inps = new UwInpsClients();

                $inps->uw_clients_id = $model_id;
                $inps->claim_id = $claim_id;
                $inps->ORG_INN = 0;
                $inps->INCOME_SUMMA = 0;
                $inps->NUM = 0;
                $inps->PERIOD = 0;
                $inps->ORGNAME = 0;
                $inps->report_json = $reportBase64;
                $inps->status = 0;
                $inps->save();
                return back()->with('error', 'Сведения о доходах по ИНПС не найдены');
            }

        }
    }

    public function getClientKatm($cid)
    {
        $model = UwKatmClients::where('claim_id', $cid)->first();

        $modelClient = UwClients::where('claim_id', $cid)->first();

        if ($model){
            $summ = $model->katm_summ;

            $scoring = json_decode($model->katm_score, true);

            $table = json_decode($model->katm_tb, true);

            $image = $model->katm_img;

        } else {
            $summ = '0';

            $scoring = 'null';

            $table = 'null';

            $image = 'null';

        }
        $getFile = file_get_contents("uw/scoring_page.php");

        $getFileScoringImg = file_get_contents("katm_files/".$cid.".php");

        return response()->json([
            'summ'   => number_format($summ),
            'scoring' => $scoring,
            'table'  => $table,
            'scoring_page'  => $getFile,
            'scoring_page1'  => $getFileScoringImg,
            'client'  => $modelClient,
            'image'  => $image
        ]);
    }

    public function getClientInps($cid)
    {
        $result = UwInpsClients::where('claim_id', $cid)->get();

        return response()->json($result);
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
        $user = UwClients::find($id);

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

    public function fileUpload(Request $request)
    {
        $validation = $this->validate($request, [
            'model_id' => 'required'
        ]);

        if($validation)
        {
            $files = $request->file('message_file');

            $model_id = $request->input('model_id');
            foreach ($files as $file) {
                if ($file != 0) {

                    $modelFile = new UwClientFiles();

                    $modelFile->uw_client_id = $model_id;

                    $modelFile->file_hash = $model_id . '_' . Auth::id() . '_' . date('dmYHis') . uniqid() . '.'
                        . $file->getClientOriginalExtension();

                    $modelFile->file_size = $file->getSize();

                    $file->move(public_path() . '/uwFiles/', $modelFile->file_hash);

                    $modelFile->file_name = $file->getClientOriginalName();

                    $modelFile->file_extension = $file->getClientOriginalExtension();

                    $modelFile->save();

                }

            }

            return response()->json(array(
                    'success' => true,
                    'message'   => 'File Successfully upload',
                    'class_name'  => 'alert-success'
                )
            );
        }
        else
        {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'uploaded_image' => '',
                'class_name'  => 'alert-danger'
            ]);
        }

    }

    // preViewPdf
    public function preViewPdf($file)
    {
        //
        if (file_exists(public_path() . "/uwFiles/" . $file)) {

            $pathToFile = public_path() . "/uwFiles/". $file;

            if(preg_match("/\.(pdf)$/", $file)) {

                return Response::make(file_get_contents($pathToFile), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="'.$file.'"'
                ]);

            }

            return response()->file($pathToFile);

        } else {

            return response()->json('Serverdan fayl topilmadi!');

        }

    }

    // download edo file
    public function downloadFile($file){

        $model = UwClientFiles::find($file);

        $headers = array(
            'Content-Type: application/octet-stream',
        );

        /*if(file_exists(public_path() . "/uwFiles/" . $model->file_hash)){

            $file= public_path(). "/uwFiles/".$model->file_hash;
            return Response::download($file, $model->file_name);

        } else {

            return back()->with('notFiles', 'Serverdan fayllar topilmadi!');
        }*/

        if (Storage::disk('ftp_nas')->exists($model->file_path.$model->file_hash)){

            return Storage::disk('ftp_nas')->download($model->file_path.$model->file_hash, $model->file_name, $headers);

        }

        return back();

    }

    public function loanAppStatistics()
    {
        //
        UwUsers::where('user_id', Auth::id())->where('status', 1)->firstorFail();

        $models = UwClients::where('status', '>=', 2)->orderBy('created_at', 'ASC')->get();

        return view('uw.uw-clients.loan-statistics', compact('models'));
    }

    public function calcForm(Request $request)
    {
        //
        if ($request->calcLoanType == 1){
            $str_summa = str_replace(',', '.', $request->calcSumma);
            $summa = str_replace(' ', '', $str_summa);
            $capital = $summa;

            $interest = $request->calcLoanInterest;

            $month = $request->calcLoanMonth;

            $next_month =  date("d.m.Y", strtotime("+0 month"));

            $begin = new \DateTime( $next_month );

            $n_month = $month / 12;
            $next_year = date("d.m.Y", strtotime("+{$n_month} year"));
            $end = new \DateTime( $next_year );
            $capital_qol = $capital/$month;
            $result = '';
            $key = 0;
            $sum = 0;
            for ($i = $begin; $i < $end; $i->modify('+1 month'))
            {
                $key++;

                $month_in_day = cal_days_in_month(CAL_GREGORIAN, $i->format('m'), $i->format('Y'));

                $loan_pers = $capital * ($interest / 100) / 365 * $month_in_day;

                $capital = $capital - $capital_qol;

                $loan_pay = $capital_qol + $loan_pers;

                $result .= '
                        <tr>
                             <td>' . $key . '</td>
                             <td>' . $i->format("d.m.Y") . '</td>
                             <td>' .number_format($capital, 2). '</td>
                             <td>' .number_format($capital_qol, 2). '</td>
                             <td>' .number_format($loan_pers, 2). '</td>
                             <td>' .number_format($loan_pay, 2). '</td>
                             <td>' .$month_in_day. '</td>
                        </tr>
                        ';
                $sum += $loan_pay;
            }

            $sum_month = number_format($sum/$month, 2);

            $models = array(
                'table_data'  => $result,
                'total_summ'  => number_format($sum),
                'total_month'  => $sum_month
            );
            echo json_encode($models);

        } elseif ($request->calcLoanType == 2) {
            $str_summa = str_replace(',', '.', $request->calcSumma);
            $vehicle_value = str_replace(' ', '', $str_summa);

            $next_month =  date("d.m.Y", strtotime("+0 month"));

            $begin = new \DateTime( $next_month );

            $n_month = $request->calcLoanMonth / 12;
            $next_year = date("d.m.Y", strtotime("+{$n_month} year"));
            $end = new \DateTime( $next_year );

            $balance = (float) $vehicle_value;
            $monthly_payment = (($request->calcLoanInterest /(100 * 12)) * $vehicle_value) / (1 - pow(1 + $request->calcLoanInterest / 1200,  (-$request->calcLoanMonth)));
            $result = '';
            $key = 0;
            for ($i = $begin; $i < $end; $i->modify('+1 month'))
            {
                $month_in_day = cal_days_in_month(CAL_GREGORIAN, $i->format('m'), $i->format('Y'));
                $interest = $balance * $request->calcLoanInterest / 1200;
                $principal = $monthly_payment - $interest;

                $key++;

                $result .= '
                        <tr>
                             <td>' . $key . '</td>
                             <td>' . $i->format("d.m.Y") . '</td>
                             <td>' .number_format($balance, 2). '</td>
                             <td>' .number_format($principal, 2). '</td>
                             <td>' .number_format($interest, 2). '</td>
                             <td>' .number_format($monthly_payment, 2). '</td>
                             <td>' .$month_in_day. '</td>
                        </tr>
                        ';
                $balance -= $principal;
            }

            $models = array(
                'table_data'  => $result,
                'total_summ'  => number_format($monthly_payment * $request->calcLoanMonth, 2),
                'total_month'  => number_format($monthly_payment, 2)
            );
            echo json_encode($models);

        }

    }

    function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'бир', 'икки', 'уч', 'тўрт', 'беш', 'олти', 'етти', 'саккиз', 'тўққиз', 'ўн', 'ўнбир',
            'ўникки', 'ўнуч', 'ўнтўрт', 'ўнбеш', 'ўнолти', 'ўнетти', 'ўнсаккиз', 'ўнтўққиз'
        );
        $list2 = array('', 'ўн', 'йигирма', 'ўттиз', 'қирқ', 'эллик', 'олтмиш', 'етмиш', 'саксон', 'тўқсон', 'юз');
        $list3 = array('', 'минг', 'миллион', 'миллиард', 'триллион', 'квадриллион', 'квинтиллион', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' юз' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }

    function transliterate($textlat = null, $textcyr = null) {
        $lat = array(
            'ch', 'sh', 'yo', 'yu', 'ya', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r',
            's', 't', 'u', 'f', 'h', 'y', 'x', 'q',
            'Ch', 'Sh', 'CH', 'SH', 'Yo', 'Yu', 'Ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R',
            'S', 'T', 'U', 'F', 'H', 'Y', 'X', 'Q');
        $cyr = array(
            'ч', 'ш', 'ё', 'ю', 'я', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'ж', 'к', 'л', 'м', 'н', 'о', 'п', 'р',
            'с', 'т', 'у', 'ф', 'ҳ', 'й', 'х', 'қ',
            'Ч', 'Ш', 'Ч', 'Ш', 'Ё', 'Ю', 'Я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Ж', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
            'С', 'Т', 'У', 'Ф', 'Х', 'Й', 'Х', 'Қ');
        if($textlat) return str_replace($lat, $cyr, $textlat);
        else if($textcyr) return str_replace($cyr, $lat, $textcyr);
        else
            return null;
    }

    public function getAppBlank(Request $request, $claim_id)
    {
        //
        $model = UwClients::findOrFail($claim_id);
        $guard_type = ' - ';
        if (isset($model->credits->credit_security)){
            if ($model->credits->credit_security == 1){
                $guard_type = 'Жисмоний шахс кафиллиги';
            } elseif ($model->credits->credit_security == 2){
                $guard_type = 'Суғурта полиси';
            } elseif ($model->credits->credit_security == 3){
                $guard_type = 'Жисмоний шахс кафиллиги + Суғурта полиси';
            }else{
                $guard_type = ' - ';
            }
        }
        $array_from_to = array (
            '[filial_name]' => $model->filial->title_ru,
            '[region_name]' => mb_convert_case($model->region->name, MB_CASE_TITLE, "UTF-8"),
            '[district_name]' => mb_convert_case($model->district->name, MB_CASE_TITLE, "UTF-8"),
            '[live_address]' => $this->transliterate($model->live_address),
            '[client_name]' => $this->transliterate($model->family_name.' '.$model->name.' '.$model->patronymic),
            '[loan_month]' => $model->credit_duration,
            '[loan_interest]' => $model->procent,
            '[loan_sum]' => number_format($model->summa),
            '[loan_sum_word]' => $this->convertNumberToWord($model->summa),
            '[guard_name]' => $model->credits->credit_security_name??'',
            '[guard_type]' => $guard_type,
            '[loan_date]' => date("d.m.Y", strtotime($model->created_at)),
            '[client_name_short]' => $this->transliterate(mb_substr($model->name, 0,1).'.'.mb_substr($model->patronymic, 0,1).'.'.$model->family_name),
        );

        $getFile = file_get_contents("uw/uw_app_blank.html");

        $print_page = str_replace(array_keys($array_from_to), $array_from_to, $getFile);

        return response()->json($print_page);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        $model = UwClients::find($id);

        $model->update(['status' => -1]);

        $modelKatm = UwKatmClients::where('uw_clients_id', $id)->first();

        if ($modelKatm)
        {
            $date = date("Y-m-d").'T'.date("H:s:i");
            $url = 'http://10.22.50.3:8001/katm-api/v1/claim/decline';
            $data = array(
                "security" => array(
                    "pLogin" => "turonbank",
                    "pPassword" => "!trB&GkL@200130"
                ),
                "data" => array(
                    "pHead" => "011",
                    "pCode" => "".Auth::user()->branch_code."",
                    "pDeclineDate" => "".$date."",
                    "pClaimId" => "".$model->claim_id."",
                    "pDeclineNumber" => "".$model->claim_number."",
                    "pDeclineReason" => "1",
                    "pDeclineReasonNote" => "Mijoz arizasini bekor qildi!"
                ),
            );
            $postdata = json_encode($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
            curl_setopt($ch, CURLOPT_PROXY, '10.22.50.3:8001');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $result = curl_exec($ch);
            curl_close($ch);

            $data_decode = json_decode($result, true);
            $code = $data_decode['code'];
            $result = $data_decode['data']['result'];
            $resultMessage = $data_decode['data']['resultMessage'];

            if ($code != '200'){
                if ($result != '05000'){
                    return response()->json(['success'=>'Mijoz o`chirishda xatolik mavjud','message' => $code.' - '.$resultMessage]);
                }
                return response()->json(['success'=>'Mijoz o`chirishda xatolik mavjud','message' => $code.' - '.$resultMessage]);
            }

        } else {
            $code = '';
            $result = '';
            $resultMessage = 'KATMga so`rov yuborilmagan';
        }

        $clientComment = new UwClientComments();
        $clientComment->uw_clients_id = $id;
        $clientComment->work_user_id = Auth::user()->currentWork->id??0;
        $clientComment->claim_id = $model->claim_id;
        $clientComment->code = $code;
        $clientComment->title = '(result:'.$result.') '.$resultMessage;
        $clientComment->comment_type = '-1';
        $clientComment->process_type = 'DEL';
        $clientComment->katm_type = 0;
        $clientComment->json_data = $result;
        $clientComment->save();

        return response()->json(['success'=>'Client Deleted successfully','message' => $resultMessage]);
    }

    public function destroyFile($id)
    {
        //
        $model = UwClientFiles::find($id);
        $file_path = public_path().'/uwFiles/'.$model->file_hash;
        if(file_exists($file_path)){
            unlink($file_path);
        }

        $model->delete();

        return response()->json(array(
                'success' => true,
                'message' => 'File Successfully deleted'
            )
        );
    }

}
