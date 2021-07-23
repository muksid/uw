<?php

namespace App\Http\Controllers;

use App\Department;
use App\MWorkUsers;
use App\User;
use App\UwJurBalanceForm;
use App\UwJurClientComment;
use App\UwJurClientFiles;
use App\UwJurClientGuars;
use App\UwJurFinancialForm;
use App\UwJuridicalClient;
use App\UwJurKatmClient;
use App\UwJurKatmFile;
use App\UwJurSaldo;
use App\UwLoanTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UwJuridicalClientsController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $status
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getClients($status)
    {
        //
        $user = Auth::user()->currentWork->id ?? 0;

        $search = UwJuridicalClient::where('work_user_id', $user)->where('status', $status);

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $d = Input::get ( 'd' );

        if($u) {
            $search->where('work_user_id', $u);
        }

        if($t) {
            $search->where(function ($query) use ($t) {

                $query->orWhere('branch_code', 'LIKE', '%' . $t . '%');
                $query->orWhere('client_code', 'LIKE', '%' . $t . '%');
                $query->orWhere('claim_id', 'LIKE', '%' . $t . '%');
                $query->orWhere('jur_name', 'LIKE', '%' . $t . '%');
                $query->orWhere('inn', 'LIKE', '%' . $t . '%');
                $query->orWhere('summa', 'LIKE', '%' . $t . '%');
            });
        }

        if($d) {
            $search->where('created_at', 'LIKE', '%'.$d.'%');
        }

        $models = $search->orderBy('id', 'DESC')->paginate(25);

        $models->appends ( array (
            'u' => Input::get ( 'u' ),
            't' => Input::get ( 't' ),
            'd' => Input::get ( 'd' )
        ) );

        $users = User::select('id')->where('status', 1)->where('isUw', 1)->get();

        $searchUser = MWorkUsers::find($u);

        return view('uw.jur-clients.ins.clients',
            compact('models','u','t','d','users','searchUser','status'));

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $status
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUwClients($status)
    {
        //
        $search = UwJuridicalClient::where('status', $status);

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $d = Input::get ( 'd' );

        if($u) {
            $search->where('work_user_id', $u);
        }

        if($t) {
            $search->where(function ($query) use ($t) {

                $query->orWhere('branch_code', 'LIKE', '%' . $t . '%');
                $query->orWhere('client_code', 'LIKE', '%' . $t . '%');
                $query->orWhere('claim_id', 'LIKE', '%' . $t . '%');
                $query->orWhere('jur_name', 'LIKE', '%' . $t . '%');
                $query->orWhere('inn', 'LIKE', '%' . $t . '%');
                $query->orWhere('summa', 'LIKE', '%' . $t . '%');
            });
        }

        if($d) {
            $search->where('created_at', 'LIKE', '%'.$d.'%');
        }

        $models = $search->orderBy('id', 'DESC')->paginate(25);

        $models->appends ( array (
            'u' => Input::get ( 'u' ),
            't' => Input::get ( 't' ),
            'd' => Input::get ( 'd' )
        ) );

        $users = User::select('id')->where('status', 1)->where('isUw', 1)->get();

        $searchUser = MWorkUsers::find($u);

        return view('uw.jur-clients.uw.clients',
            compact('models','u','t','d','users','searchUser','status'));

    }
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllClients()
    {
        //
        $search = UwJuridicalClient::orderBy('id', 'DESC');

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $d = Input::get ( 'd' );

        if($u) {
            $search->where('work_user_id', $u);
        }

        if($t) {
            $search->where(function ($query) use ($t) {

                $query->orWhere('branch_code', 'LIKE', '%' . $t . '%');
                $query->orWhere('client_code', 'LIKE', '%' . $t . '%');
                $query->orWhere('claim_id', 'LIKE', '%' . $t . '%');
                $query->orWhere('jur_name', 'LIKE', '%' . $t . '%');
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

        return view('uw.jur-clients.ins.all-clients',
            compact('models','u','t','d','users','searchUser'));

    }

    public function getSelect(Request $request)
    {
        //
        $filial = 1;

        $tin = 1;

        $type = 'q';

        $data = $this->curlHttpPost($filial, $tin, $type);

        $models = json_decode($data);

        return response()->json($models);
    }

    public function getHrEmps(Request $request)
    {
        //
        $filial = 1;

        $tin = 1;

        $type = 'hr';

        $data = $this->curlHttpPost($filial, $tin, $type);

        $models = json_decode($data);

        return response()->json($models);
    }

    public function curlHttpPost($filial, $p1, $type)
    {
        $user = 'muksid_iabs';
        $pass = 'zz0102031!@#$';

        if ($type == 'c'){
            $data = array('user' => $user, 'pass' => $pass, 'inn' => $p1);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-jur';

        } elseif ($type == 's'){
            $data = array('user' => $user, 'pass' => $pass, 'code_filial' => $filial, 'client_code' => $p1);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-jur-saldo';

        } elseif ($type == 'saldo'){
            $now = Carbon::now();
            $y = $now->year-1;
            $m = $now->month;
            $d = $now->day;
            $year = Carbon::create($y, $m, $d)->format('d-m-Y');

            $query = "
            select a.code_filial, a.acc_external,a.name,a.turnover_all_credit as all_credit, b.turnover_all_credit as credit,
            a.turnover_all_debit as all_debit, b.turnover_all_debit as debit,a.lead_last_date as l_date,
            b.lead_last_date as f_date, a.code_currency 
            from accounts a
            left join saldo b on a.id = b.id
            where a.code_filial = '".$filial."' and a.client_code = '".$p1."' and a.code_coa = '20208' and a.code_currency = '000' and b.lead_last_date >= to_date('".$year."', 'dd.MM.yyyy')
            order by a.lead_last_date asc OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
            ";

            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'q'){
            $query = "
            select * from saldo a
            where a.id = 3539110 and a.lead_last_date between to_date('14.07.2020', 'dd.MM.yyyy') and to_date('14.07.2021', 'dd.MM.yyyy')
            order by a.lead_last_date desc
            ";

            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'hr'){
            $query = "
            select * from hr_emps a
            where 1=1 and a.condition = 'A'
            order by a.creation_date desc
            ";

            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        }

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);

        return $result;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOraSearch(Request $request)
    {
        //
        $filial = $request->input('filial');

        $tin = $request->input('tin');

        $type = 'c';

        $data = $this->curlHttpPost($filial, $tin, $type);

        $models = json_decode($data);

        return response()->json($models);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  integer $tin
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOraData($tin)
    {
        //
        $type = 'c';
        $filial = '';
        $data = $this->curlHttpPost($filial, $tin, $type);

        $models = json_decode($data, true);

        $model = $models[0];

        $loans = UwLoanTypes::where('isActive', 1)->where('short_code', 'J')->get();

        return view('uw.jur-clients.ins.view-form', compact('model', 'loans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOraSaldo(Request $request)
    {
        //
        $id = $request->id;
        $filial = $request->filial;
        $code_client = $request->code;
        $type = 'saldo';

        $data = $this->curlHttpPost($filial, $code_client, $type);

        $models = json_decode($data, true);

        if ($models){
            UwJurSaldo::where('jur_clients_id', $id)->delete();

            foreach ($models  as $model){

                $date1 = date('Y-m-d',strtotime($model['f_date']));
                $date2 = date('Y-m-d',strtotime($model['l_date']));

                $ts1 = strtotime($date1);
                $ts2 = strtotime($date2);

                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);

                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

                $saldo = new UwJurSaldo();
                $saldo->jur_clients_id = $id;
                $saldo->code_filial = $model['code_filial'];
                $saldo->client_name = $model['name'];
                $saldo->client_acc = $model['acc_external'];
                $saldo->all_credit = $model['all_credit']/100;
                $saldo->credit = ($model['all_credit']-$model['credit'])/100;
                $saldo->debit = $model['all_debit']/100;
                $saldo->all_debit = $model['all_debit']/100;
                $saldo->debit = ($model['all_debit']-$model['debit'])/100;
                $saldo->curr = $model['code_currency'];
                $saldo->saldo_month = $diff;
                $saldo->lead_last_date = $model['l_date'];
                $saldo->save();
            }
        }

        return response()->json($models);
    }


    public function clientCreditResults($id)
    {
        //
        $model = UwJuridicalClient::find($id);

        $loan_type = UwLoanTypes::find($model->loan_type_id);

        $saldo = UwJurSaldo::where('jur_clients_id', $id)->where('curr', '000')->first();

        $kias = UwJurKatmClient::where('jur_clients_id', $id)->where('status', 1)->first();

        $saldo_credit = 0;

        $kias_summa = 0;

        $kias_ball = 0;

        if ($saldo){
            $saldo_credit = $saldo->credit;
        }

        if ($kias){
            $kias_summa = $kias->summa;
            $kias_ball = $kias->scoring_ball;
        }

        $monthly_payment = $model->summa/$loan_type->credit_duration+$model->summa*$loan_type->procent*0.01/365*30;
        $mavjud_kredit_buy_oylik_tulov = $kias_summa;
        $real_monthly_payment = +($saldo_credit*1)/12*0.25-$kias_summa;
        $jami_tuley_farqi = $real_monthly_payment - $monthly_payment;
        $credit_can_be = $real_monthly_payment * $loan_type->credit_duration/(+$loan_type->credit_duration*$loan_type->procent*0.01/365*30+1);


        return [
            'credit_debt' => $kias_summa,
            'credit_sum' => $model->summa,
            'credit_can_be' => $credit_can_be,
            'scoring_ball' => $kias_ball,
            'monthly_payment' => $monthly_payment,
            'jami_tuley_farqi' => $jami_tuley_farqi,
            'real_monthly_payment' => $real_monthly_payment
        ];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKiasModal(Request $request)
    {
        //
        $id = $request->id;

        $model = UwJurKatmFile::where('jur_clients_id', $id)->where('file_type', 'B64')->orderBy('id', 'DESC')->first();
        if ($model){
            $path = '/'.$model->file_hash;

            if (Storage::disk('ftp_nas')->exists($path)){

                $data = Storage::disk('ftp_nas')->get($path);

                $base64 = base64_decode($data);

                $array = json_decode($base64, true);

                return response()->json($array);

            } else {
                return response()->json([
                    'error' => 'Error Scoring KIAS PATH!'
                ]);
            }


        }
        return response()->json([
            'error' => 'Error Scoring KIAS!'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
        return view('uw.jur-clients.ins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
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

        $rules = array(
            'hbranch' => 'required|max:10',
            'summa' => 'required',
            'owner_form' => 'required|max:5',
            'code_juridical_person' => 'required|max:10',
            'phone' => 'required',
            'oked' => 'required|max:10',
            'okpo' => 'required|max:10'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){

            return Redirect::to(url('/jur/view-form/'.$request->inn))
                ->withErrors($validator);

        } else{

            $branchCode = $currentWorkUser->branch_code;

            $localCode = Department::find($currentWorkUser->depart_id);

            $lastModelId = UwJuridicalClient::where('branch_code', '=', $branchCode)->latest()->first();
            $claim_id = 1000;
            if ($lastModelId){
                $claim_id = $lastModelId->claim_number + 1;
            }

            $phone = preg_replace('/[^A-Za-z0-9]/', '', $request->phone);
            $str_summa = str_replace(',', '.', $request->summa);
            $summa = str_replace(' ', '', $str_summa);

            $model  = new UwJuridicalClient();
            $model->claim_id = '2'.$branchCode.$claim_id;
            $model->claim_date = today();
            $model->inn = $request->inn;
            $model->claim_number = $claim_id;
            $model->agreement_number = $claim_id;
            $model->agreement_date = today();
            $model->resident = $request->resident;
            $model->juridical_status = 1;
            $model->nibbd = '';
            $model->client_type = $request->client_type;
            $model->jur_name = $request->jur_name;
            $model->live_cadastr = '';
            $model->owner_form = $request->owner_form;
            $model->goverment = 1;
            $model->registration_region = $request->registration_region;
            $model->registration_district = $request->registration_district;
            $model->registration_address = $request->registration_address;
            $model->phone = $phone;
            $model->hbranch = $request->hbranch;
            $model->oked = $request->oked;
            $model->okpo = $request->okpo;
            $model->code_juridical_person = $request->code_juridical_person;
            $model->summa = $summa;
            $model->client_code = $request->client_code;
            $model->loan_type_id = $request->loan_type_id;
            $model->work_user_id = $currentWorkUser->id;
            $model->branch_code = $branchCode;
            $model->local_code = $localCode->local_code;
            $model->save();

            return Redirect::to('/jur/clients/1')
                ->with(['success' => 'Mijoz muvaffaqiyatli tizimga qo`shildi']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //
        $model = UwJuridicalClient::findOrFail($id);

        $kias = UwJurKatmClient::where('jur_clients_id', $id)->where('status', 1)->first();

        if ($kias){

            $kias_decode = base64_decode($kias->json_data);

            $kias_table = json_decode($kias_decode, true);
        } else{
            $kias = '';
            $kias_table = '';
        }

        $balance = UwJurBalanceForm::where('uw_jur_clients_id', $id)->where('isActive', 1)->first();

        $financial = UwJurFinancialForm::where('uw_jur_clients_id', $id)->where('isActive', 1)->first();

        $saldos = UwJurSaldo::where('jur_clients_id', $id)->get();

        $credit_result = $this->clientCreditResults($id);

        $guars = UwJurClientGuars::where('jur_clients_id', $id)->get();

        $files = UwJurClientFiles::where('jur_clients_id', $id)->get();

        $modelComments = UwJurClientComment::where('jur_clients_id', $id)->get();

        return view('uw.jur-clients.ins.view-client',
            compact('model', 'kias', 'kias_table', 'balance', 'financial', 'saldos', 'credit_result', 'guars', 'files', 'modelComments')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uwShow($id)
    {
        //
        $model = UwJuridicalClient::findOrFail($id);

        $kias = UwJurKatmClient::where('jur_clients_id', $id)->where('status', 1)->first();

        if ($kias){

            $kias_decode = base64_decode($kias->json_data);

            $kias_table = json_decode($kias_decode, true);
        } else{
            $kias = '';
            $kias_table = '';
        }

        $balance = UwJurBalanceForm::where('uw_jur_clients_id', $id)->where('isActive', 1)->first();

        $financial = UwJurFinancialForm::where('uw_jur_clients_id', $id)->where('isActive', 1)->first();

        $saldos = UwJurSaldo::where('jur_clients_id', $id)->get();

        $credit_result = $this->clientCreditResults($id);

        $guars = UwJurClientGuars::where('jur_clients_id', $id)->get();

        $files = UwJurClientFiles::where('jur_clients_id', $id)->get();

        $modelComments = UwJurClientComment::where('jur_clients_id', $id)->get();

        return view('uw.jur-clients.uw.view',
            compact('model', 'kias', 'kias_table', 'balance', 'financial', 'saldos', 'credit_result', 'guars', 'files', 'modelComments')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //
        $model = UwJuridicalClient::findOrFail($id);

        $loans = UwLoanTypes::where('isActive', 1)->where('short_code', 'J')->get();

        $guars = UwJurClientGuars::where('jur_clients_id', $id)->get();

        $files = UwJurClientFiles::where('jur_clients_id', $id)->get();

        return view('uw.jur-clients.ins.edit-client', compact('model', 'loans', 'guars', 'files'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        //
        $model = UwJuridicalClient::findOrFail($id);

        $phone = preg_replace('/[^A-Za-z0-9]/', '', $request->phone);
        $str_summa = str_replace(',', '.', $request->summa);
        $summa = str_replace(' ', '', $str_summa);

        $rules = array(
            'hbranch' => 'required|max:10',
            'summa' => 'required',
            'owner_form' => 'required|max:5',
            'phone' => 'required',
            'oked' => 'required|max:10',
            'okpo' => 'required|max:10'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){

            return Redirect::to('/jur/client/'.$id.'/edit')
                ->withErrors($validator);

        } else{

            $inputs = $request->only('hbranch', 'summa', 'owner_form', 'phone', 'oked', 'okpo', 'loan_type_id');

            $inputs['summa'] = $summa;

            $inputs['phone'] = $phone;

            $model->update($inputs);

            return Redirect::to('/jur/client/'.$id)
                ->with('success', 'Mijoz ma`lumotlari muvaffaqiyatli yangilandi');

        }
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function guarStore(Request $request)
    {
        //
        $rules = array(
            'jur_clients_id' => 'required',
            'guar_type' => 'required',
            'guar_sum' => 'required',
            'title' => 'required|max:255'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){

            return Redirect::to(route('client.edit', $request->jur_clients_id))
                ->withErrors($validator);

        } else{

            $str_summa = str_replace(',', '.', $request->guar_sum);
            $summa = str_replace(' ', '', $str_summa);

            $model  = new UwJurClientGuars();
            $model->jur_clients_id = $request->jur_clients_id;
            $model->guar_type = $request->guar_type;
            $model->title = $request->title;
            $model->guar_sum = $summa;
            $model->save();

            return Redirect::to(route('client.edit', $request->jur_clients_id))
                ->with('success', 'Kafil ma`lumotlari muvaffaqiyatli saqlandi');

        }

    }

    public function guarDelete($id)
    {
        $guar = UwJurClientGuars::find($id);
        $guar->delete();
        return back()->with('success', 'Kafil o`chirildi');
    }

    public function filesStore(Request $request)
    {
        $id = $request->jur_clients_id;

        $files = $request->file('files');

        if ($files) {
            $today = Carbon::today();
            $year = $today->year;
            $month = $today->month;
            $day = $today->day;
            $path = 'uw/jur/files/'.$year.'/'.$month.'/'.$day.'/';
            foreach ($files as $file) {
                if ($file != 0) {
                    $model = new UwJurClientFiles();
                    $model->jur_clients_id = $id;
                    $model->file_path = $path;
                    $model->file_hash = md5(date('dmYHis')).'_'.$id.'.'.$file->getClientOriginalExtension();
                    $model->file_size = $file->getSize();
                    Storage::disk('ftp_nas')->put($path.$model->file_hash, file_get_contents($file->getRealPath()));
                    $model->file_name = $file->getClientOriginalName();
                    $model->file_extension = $file->getClientOriginalExtension();
                    $model->save();
                }
            }
            return back()->with('success', 'Ilovalar muvaffaqiyatli saqlandi');

        } else {
            return back()->with('success', 'Ilova biriktiring!!!');
        }
    }

    public function fileDownload($file){

        $model = UwJurClientFiles::find($file);

        $headers = array(
            'Content-Type: application/octet-stream',
        );

        if (Storage::disk('ftp_nas')->exists($model->file_path.$model->file_hash)){

            return Storage::disk('ftp_nas')->download($model->file_path.$model->file_hash, $model->file_name, $headers);

        }

        return back();

    }

    public function fileDelete($id)
    {

        $model = UwJurClientFiles::find($id);

        $model->delete();

        if (Storage::disk('ftp_nas')->exists($model->file_path.$model->file_hash)){

            Storage::disk('ftp_nas')->delete($model->file_path.$model->file_hash);

        }

        return back()->with('success', 'Ilova o`chirildi');
    }

    public function sendToAdmin(Request $request)
    {
        $id = $request->id;

        $model = UwJuridicalClient::find($id);

        $kias = UwJurKatmClient::where('jur_clients_id',$id)->first();

        $canBeCalc = $this->clientCreditResults($id);

        $credit_sum = $canBeCalc['credit_sum'];
        $credit_canBe = $canBeCalc['credit_can_be'];

        if ($credit_sum >= $credit_canBe){
            $code = 0;
            $message = 'Kredit summasi yetarli emas!!!';
        } elseif (!$model->katm_sir){
            $code = 0;
            $message = 'Mijoz KATM dasturidan ro`yhatdan o`tmagan!!!';
        } elseif (!$kias){
            $code = 0;
            $message = 'KATM KIAS Scoring natijasi tpilmadi!!!';
        } else {
            $model = UwJuridicalClient::find($id);
            $model->update(['status' => 2]);

            $code = 1;
            $message = 'Ariza Anderrayterga yuborildi';
        }

        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $canBeCalc,
        ]);
    }

    public function agrConfirm(Request $request){

        $id = $request->input('jur_clients_id');

        $model = UwJuridicalClient::find($id);
        $model->update(['status' => 3]);

        // Risk admin comments
        $modelComment = new UwJurClientComment();
        $modelComment->jur_clients_id = $id;
        $modelComment->work_user_id = Auth::user()->currentWork->id??0;
        $modelComment->title = 'Anderrayter tomonidan tasdiqlangan';
        $modelComment->process_type = 'CONF';
        $modelComment->save();

        return response()->json(array('success' => true, 'msg' => $id));
    }

    public function agrCancel(Request $request){

        $id = $request->input('jur_clients_id');

        $descr = $request->input('descr');

        $model = UwJuridicalClient::find($id);
        $model->update(['status' => 0]);

        // Risk admin comments
        $modelComment = new UwJurClientComment();
        $modelComment->jur_clients_id = $id;
        $modelComment->work_user_id = Auth::user()->currentWork->id??0;
        $modelComment->title = 'Ariza tahrirlashda - '.$descr;
        $modelComment->process_type = 'CACN';
        $modelComment->save();

        return response()->json(array('success' => true));
    }

}
