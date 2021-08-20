<?php

namespace App\Http\Controllers;

use App\Department;
use App\MWorkUsers;
use App\User;
use App\UwJurBalanceChild;
use App\UwJurBalanceForm;
use App\UwJurClientComment;
use App\UwJurClientFiles;
use App\UwJurClientGuars;
use App\UwJurClientPersonal;
use App\UwJurFinancialChild;
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

        return view('jur.ins.clients',
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

        return view('jur.uw.clients',
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

        return view('jur.uw.all-clients',
            compact('models','u','t','d','users','searchUser'));

    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
        return view('jur.ins.create');
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
            //'oked' => 'required|max:10',
            //'okpo' => 'required|max:10'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){

            return Redirect::to(url('/jur/view-form/'.$request->inn))
                ->withErrors($validator);

        } else{

            $branchCode = $currentWorkUser->branch_code;

            //$localCode = Department::find($currentWorkUser->depart_id);

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
            $model->local_code = $currentWorkUser->local_code;
            $model->save();

            return Redirect::to('/jur/clients/1')
                ->with(['success' => 'Mijoz muvaffaqiyatli tizimga qo`shildi']);

        }

    }

    // CREDIT INSPECTOR VIEW
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

        $k2 = UwJurSaldo::where('jur_clients_id', $id)->get();

        $credit_result = $this->clientCreditResults($id);

        $guars = UwJurClientGuars::where('jur_clients_id', $id)->get();

        $files = UwJurClientFiles::where('jur_clients_id', $id)->get();

        $modelComments = UwJurClientComment::where('jur_clients_id', $id)->get();

        $personal = UwJurClientPersonal::where('jur_clients_id', $id)->first();

        $balance_class = $this->balanceClass($id);

        //print_r($kias_table); die;

        return view('jur.ins.view',
            compact('model', 'kias', 'kias_table', 'balance', 'financial', 'saldos', 'k2','credit_result',
                'guars', 'files', 'modelComments', 'personal', 'balance_class')
        );
    }

    // UNDERWRITER VIEW
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

        $k2 = UwJurSaldo::where('jur_clients_id', $id)->get();

        $credit_result = $this->clientCreditResults($id);

        $guars = UwJurClientGuars::where('jur_clients_id', $id)->get();

        $files = UwJurClientFiles::where('jur_clients_id', $id)->get();

        $modelComments = UwJurClientComment::where('jur_clients_id', $id)->get();

        $personal = UwJurClientPersonal::where('jur_clients_id', $id)->first();

        $balance_class = $this->balanceClass($id);

        return view('jur.uw.view',
            compact('model', 'kias', 'kias_table', 'balance', 'financial', 'saldos', 'credit_result',
                'guars', 'files', 'modelComments', 'k2', 'personal', 'balance_class')
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

        return view('jur.ins.edit', compact('model', 'loans', 'guars', 'files'));
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
            //'oked' => 'required|max:10',
            //'okpo' => 'required|max:10'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){

            return Redirect::to('/jur/client/'.$id.'/edit')
                ->withErrors($validator);

        } else {

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

    public static function curlHttpPost($array)
    {
        $user = 'muksid_iabs';
        $pass = 'zz0102031!@#$';
        $type = $array['type'];

        if ($type == 'cc'){
            $client_code = $array['client_code'];
            $query = "
            select a.code,a.name,a.typeof,a.inn,a.address,a.resident_code,a.country_code,a.tax_organization_code,
                a.property_form_code,a.phone,a.date_validate,a.code_filial,a.id,a.region_code,c.name as reg_name,a.district_code,d.name as dis_name,a.date_open,
                b.director_name,b.accounter_chief_name,b.sector_code,b.organ_directive_code,b.registration_place_code,
                b.organization_legal_form,b.address_code,b.code_juridical_person,b.main_filial,b.main_account,b.oked,
                b.organization_head_code,b.registration_document_number
                from client_current a
                left join client_juridical_current b on a.id = b.id
                left join ref_region c on a.region_code = c.code
                left join ref_district d on a.district_code = d.code
                where a.subject = 'J' and a.condition = 'A' and c.version_id = 40 and d.version_id = 40 and a.code = '".$client_code."'
            ";

            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';

        } elseif ($type == 'id'){
            $id = $array['id'];
            $query = "
            select a.code,a.name,a.typeof,a.inn,a.address,a.resident_code,a.country_code,a.tax_organization_code,
                a.property_form_code,a.phone,a.date_validate,a.code_filial,a.id,a.region_code,c.name as reg_name,a.district_code,d.name as dis_name,a.date_open,
                b.director_name,b.accounter_chief_name,b.sector_code,b.organ_directive_code,b.registration_place_code,
                b.organization_legal_form,b.address_code,b.code_juridical_person,b.main_filial,b.main_account,b.oked,
                b.organization_head_code,b.registration_document_number
                from client_current a
                left join client_juridical_current b on a.id = b.id
                left join ref_region c on a.region_code = c.code
                left join ref_district d on a.district_code = d.code
                where a.subject = 'J' and a.condition = 'A' and c.version_id = 40 and d.version_id = 40 and a.id = '".$id."'
            ";

            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';

        }
        elseif ($type == 'saldo'){
            $last_year = date('d.m.Y', strtotime('-1 year'));
            $filial = $array['filial'];
            $client_code = $array['client_code'];
            $coa = $array['coa'];
            $query = "
            select a.code_filial, a.acc_external,a.name,a.turnover_all_credit as all_credit, b.turnover_all_credit as credit,
            a.turnover_all_debit as all_debit, b.turnover_all_debit as debit,a.lead_last_date as l_date,
            b.lead_last_date as f_date, a.code_currency 
            from accounts a
            left join saldo b on a.id = b.id
            where a.code_filial = '".$filial."' and a.client_code = '".$client_code."' and a.code_coa = '".$coa."' and a.code_currency = '000' and b.lead_last_date >= to_date('".$last_year."', 'dd.MM.yyyy')
            order by b.lead_last_date asc OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
            ";

            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'leads'){
            $last_3_m = date('d.m.Y', strtotime('-3 month'));
            $filial = $array['filial'];
            $client_code = $array['client_code'];
            $coa = $array['coa'];
            $query = "
            select max(a.acc_external) as acc, to_char(b.lead_last_date, 'MM.yyyy') as l_date, max(b.lead_last_date),sum(a.turnover_all_credit) as all_credit, 
            sum(b.turnover_credit) as credit from accounts a
            left join saldo b on a.id = b.id
            where a.code_filial = '".$filial."' and a.client_code = '".$client_code."' and a.code_coa = '".$coa."' and a.code_currency = '000'
            and b.lead_last_date > to_date('".$last_3_m."', 'dd.MM.yyyy')
            group by to_char(b.lead_last_date, 'MM.yyyy')
            order by to_char(b.lead_last_date, 'MM.yyyy')
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'k2'){
            $filial = $array['filial'];
            $client_code = $array['client_code'];
            $query = "
            select * from accounts a
            where 1=1 and a.code_filial = '".$filial."' and a.client_code = '".$client_code."' and a.code_coa = '90963'
            ";

            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'emp'){
            $emp_code = $array['emp_code'];
            $query = "
            select a.*,b.department_code,c.department_name,d.begin_date as begin_work_date,d.work_dep,d.work_post 
            from hr_emps a, hr_staffing b, hr_s_departments c, hr_emp_works d
            where 1=1 and A.CONDITION != 'P' and a.staffing_id = b.staffing_id and b.department_code = c.code 
            and a.emp_id = d.emp_id 
            and d.work_now = 'Y' 
            and (a.tab_num like '".$emp_code."' or upper(a.last_name|| ' ' || a.first_name|| ' ' || a.middle_name) like '%".$emp_code."%')            
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'emp_upd_personal'){
            $cb_id = $array['cb_id'];
            $query = "
            select a.* from hr_emps a
            where 1=1 and a.cb_id = '".$cb_id."' and  A.CONDITION != 'P'          
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'get_emp_insert'){
            $cb_id = $array['cb_id'];
            $query = "
            select a.*,b.department_code,c.department_name,d.begin_date as begin_work_date,d.work_dep,d.work_post 
            from hr_emps a
            left join hr_staffing b on a.staffing_id = b.staffing_id
            left join hr_s_departments c on b.department_code = c.code
            left join hr_emp_works d on a.emp_id = d.emp_id and d.work_now = 'Y'
            where 1=1 and a.cb_id = '".$cb_id."' and  A.CONDITION != 'P'          
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'filial_upd'){
            $query = "
            select a.* from vl_filials a
            where 1=1 and a.condition = 'A' and a.code_level in (1,2,3)
            order by a.code_level, a.code
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'sdep_upd'){
            $query = "
            select a.* from hr_s_departments a
            where 1=1
            order by a.code
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'role_dep_upd'){
            $branch_code = $array['branch_code'];
            $query = "
            select a.* from hr_departments a
            where a.filial = '".$branch_code."'
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'emp_get_last_work'){
            $cb_id = $array['cb_id'];
            $query = "
            select a.filial,a.emp_id,a.tab_num,a.last_name,c.parent_code,c.code,c.condition,d.work_now,d.begin_date,d.end_date,
            d.work_post,d.bank_branch_code from hr_emps a
            left join hr_staffing b on a.staffing_id = b.staffing_id
            left join hr_departments c on c.code = b.department_code and b.filial = c.filial
            left join hr_emp_works d on a.emp_id = d.emp_id
            where 1=1 and a.cb_id = '".$cb_id."' and d.work_now = 'Y'
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        }

        /*elseif ($type == 'emp_get_cb_ids'){
            $filial = $array['filial'];
            //$emp_code = $array['emp_code'];
            $query = "
            select a.cb_id, max(a.emp_id) as emp_id, max(a.tab_num) as tab_num, max(a.filial) as filial from hr_emps a
            where a.condition != 'P' and a.filial = '".$filial."'
            group  by a.cb_id
            ";
            $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
        } elseif ($type == 'dep_upd'){
            $branch_code = $array['branch_code'];
            $query = "
            select a.filial,a.code,a.parent_code,a.condition,a.order_by,b.code,b.department_name from hr_departments a
            left join hr_s_departments b on a.code = b.code
            where 1=1 and a.condition = 'A' and a.filial = '".$branch_code."' and b.code != '000000'
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
        }*/


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
        $client_code = $request->input('client_code');

        $type = 'cc';

        $array = array('client_code' => $client_code, 'type' => $type);

        $data = $this->curlHttpPost($array);

        $models = json_decode($data);

        return response()->json($models);
    }

    public function getOraEmpSearch(Request $request)
    {
        //
        $client_code = $request->input('emp_code');

        $type = 'emp';

        $array = array('emp_code' => $client_code, 'type' => $type);

        $data = $this->curlHttpPost($array);

        $models = json_decode($data);

        return response()->json($models);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  integer $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOraData($id)
    {
        //
        $type = 'id';

        $array = array('id' => $id, 'type' => $type);

        $data = $this->curlHttpPost($array);

        $models = json_decode($data, true);
        $model = $models[0];

        $loans = UwLoanTypes::where('isActive', 1)->where('short_code', 'J')->get();

        return view('jur.ins.form', compact('model', 'loans'));
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
        $model = UwJuridicalClient::find($id);
        $filial = $model->branch_code;
        $client_code = $model->client_code;
        if ($model->client_type == '11'){
            $coa = '20218';
        } else {
            $coa = '20208';
        }
        $type = 'saldo';
        $array = array('filial' => $filial, 'client_code' => $client_code, 'coa' => $coa, 'type' => $type);

        $data = $this->curlHttpPost($array);

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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOraK2(Request $request)
    {
        //
        $id = $request->id;
        $model = UwJuridicalClient::find($id);
        $filial = $model->branch_code;
        $client_code = $model->client_code;
        $type = 'k2';
        $array = array('filial' => $filial, 'client_code' => $client_code, 'type' => $type);

        $data = $this->curlHttpPost($array);

        $models = json_decode($data, true);

        UwJurSaldo::updateOrCreate(['jur_clients_id' => $id],
            [
                'jur_clients_id' => $id,
                'k2' => $models[0]['saldo_unlead']/100,
                'lead_last_date' => $models[0]['lead_last_date']
            ]);

        return response()->json($models);
    }

    public function getOraLeads(Request $request)
    {
        //
        $id = $request->id;
        $model = UwJuridicalClient::find($id);
        $filial = $model->branch_code;
        $client_code = $model->client_code;
        if ($model->client_type == '11'){
            $coa = '20218';
        } else {
            $coa = '20208';
        }
        $type = 'leads';
        $array = array('filial' => $filial, 'client_code' => $client_code, 'coa' => $coa, 'type' => $type);

        $data = $this->curlHttpPost($array);

        $models = json_decode($data, true);

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

        $credit_can_be_ins = $credit_can_be;
        if ($credit_can_be_ins >= $model->summa){
            $credit_can_be_ins = $model->summa;
        }


        return [
            'credit_debt' => $kias_summa,
            'credit_sum' => $model->summa,
            'credit_can_be' => $credit_can_be,
            'credit_can_be_ins' => $credit_can_be_ins,
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

    public function f1cRowNo($id, $value)
    {
        $balance_child = UwJurBalanceChild::where('uw_jur_balance_id', $id)->where('row_no', "=",$value)->first();

        return $balance_child->sum_begin_period??0.00;
    }

    public function f1eRowNo($id, $value)
    {
        $balance_child = UwJurBalanceChild::where('uw_jur_balance_id', $id)->where('row_no', "=",$value)->first();

        return $balance_child->sum_end_period??0.00;
    }

    public function f2cRowNo($id, $value)
    {
        $financial_child = UwJurFinancialChild::where('uw_jur_financial_id', $id)->where('row_no', "=",$value)->first();

        return $financial_child->sum_old_period_doxod??0.00;
    }

    public function f2dRowNo($id, $value)
    {
        $financial_child = UwJurFinancialChild::where('uw_jur_financial_id', $id)->where('row_no', "=",$value)->first();

        return $financial_child->sum_old_period_rasxod??0.00;
    }

    public function f2eRowNo($id, $value)
    {
        $financial_child = UwJurFinancialChild::where('uw_jur_financial_id', $id)->where('row_no', "=",$value)->first();

        return $financial_child->sum_period_doxod??0.00;
    }

    public function f2fRowNo($id, $value)
    {
        $financial_child = UwJurFinancialChild::where('uw_jur_financial_id', $id)->where('row_no', "=",$value)->first();

        return $financial_child->sum_period_rasxod??0.00;
    }

    public function balanceClass($id)
    {
        //
        $balance = UwJurBalanceForm::where('uw_jur_clients_id', $id)->first();
        $f1 = $balance->id??'';

        $financial = UwJurFinancialForm::where('uw_jur_clients_id', $id)->first();
        $f2 = $financial->id??'';

        $res_table = '';

        if ($balance and $financial) {

            $f1_row_c_5 = (($this->f1cRowNo($f1,"480") - $this->f1cRowNo($f1,"290")) + $this->f1cRowNo($f1,"490")) - $this->f1cRowNo($f1,"130");
            $f1_row_e_5 = (($this->f1eRowNo($f1,"480") - $this->f1eRowNo($f1,"290")) + $this->f1eRowNo($f1,"490")) - $this->f1eRowNo($f1,"130");

            $dv_f1cRowNo = (0.00 == $this->f1cRowNo($f1,"600")) ? 1 : $this->f1cRowNo($f1,"600");
            $f1_row_c_6 = ($this->f1cRowNo($f1,"320") + $this->f1cRowNo($f1,"210")) / $dv_f1cRowNo;

            $dv_f1eRowNo = (0.00 == $this->f1eRowNo($f1,"600")) ? 1 : $this->f1eRowNo($f1,"600");
            $f1_row_e_6 = ($this->f1eRowNo($f1,"320") + $this->f1eRowNo($f1,"210")) / $dv_f1eRowNo;

            $f1_row_c_7 = ($this->f1cRowNo($f1,"140") + $this->f1cRowNo($f1,"210") + $this->f1cRowNo($f1,"320")) / $dv_f1cRowNo;
            $f1_row_e_7 = ($this->f1eRowNo($f1,"140") + $this->f1eRowNo($f1,"210") + $this->f1eRowNo($f1,"320")) / $dv_f1eRowNo;

            $dv_f2cRowNo = (0.00 == $this->f2cRowNo($f2,"010")) ? 1 : $this->f2cRowNo($f2,"010");
            $f1_row_c_9 = $this->f1cRowNo($f1,"210") / $dv_f2cRowNo * 360;

            $dv_f2eRowNo = (0.00 == $this->f2eRowNo($f2,"010")) ? 1 : $this->f2eRowNo($f2,"010");
            $f1_row_e_9 = $this->f1eRowNo($f1,"210") / $dv_f2eRowNo * 90;

            // row 10
            $dv_f2dRowNo = (0.00 == $this->f2dRowNo($f2,"020")) ? 1 : $this->f2dRowNo($f2,"020");
            $f1_row_c_10 = $this->f1cRowNo($f1,"601") / $dv_f2dRowNo * 360;

            $dv_f2fRowNo = (0.00 == $this->f2fRowNo($f2,"020")) ? 1 : $this->f2fRowNo($f2,"020");
            $f1_row_e_10 = $this->f1eRowNo($f1,"601") / $dv_f2fRowNo * 90;

            // row 11
            $dv_f2cRowNo_11 = (0.00 == $this->f2cRowNo($f2,"010")) ? 1 : $this->f2cRowNo($f2,"010");
            $f1_row_c_11 = $this->f1cRowNo($f1,"170") / $dv_f2cRowNo_11 * 360;

            $dv_f2eRowNo_11 = (0.00 == $this->f2eRowNo($f2,"010")) ? 1 : $this->f2eRowNo($f2,"010");
            $f1_row_e_11 = $this->f1eRowNo($f1,"170") / $dv_f2eRowNo_11 * 90;

            // row 12
            $dv_f2cRowNo_12 = (0.00 == $this->f2cRowNo($f2,"010")) ? 1 : $this->f2cRowNo($f2,"010");
            $f1_row_c_12 = $this->f1cRowNo($f1,"180") / $dv_f2cRowNo_12 * 360;

            $dv_f2eRowNo_12 = (0.00 == $this->f2eRowNo($f2,"010")) ? 1 : $this->f2eRowNo($f2,"010");
            $f1_row_e_12 = $this->f1eRowNo($f1,"180") / $dv_f2eRowNo_12 * 90;

            // row 13
            $dv_f2cRowNo_13 = (0.00 == $this->f2dRowNo($f2,"020")) ? 1 : $this->f2dRowNo($f2,"020");
            $f1_row_c_13 = $this->f1cRowNo($f1,"150") / $dv_f2cRowNo_13 * 360;

            $dv_f2eRowNo_13 = (0.00 == $this->f2fRowNo($f2,"020")) ? 1 : $this->f2fRowNo($f2,"020");
            $f1_row_e_13 = $this->f1eRowNo($f1,"150") / $dv_f2eRowNo_13 * 90;

            // row 14
            $dv_f2cRowNo_14 = (0.00 == $this->f2cRowNo($f2,"010")) ? 1 : $this->f2cRowNo($f2,"010");
            $f1_row_c_14 = (($this->f1cRowNo($f1,"012") + $this->f1cRowNo($f1,"022")) / $dv_f2cRowNo_14) * 360;

            $dv_f2eRowNo_14 = (0.00 == $this->f2eRowNo($f2,"010")) ? 1 : $this->f2eRowNo($f2,"010");
            $f1_row_e_14 = (($this->f1eRowNo($f1,"012") + $this->f1eRowNo($f1,"022")) / $dv_f2eRowNo_14) * 90;

            // row 15
            $dv_f1cRowNo_15 = (0.00 == $this->f1cRowNo($f1,"400")) ? 1 : $this->f1cRowNo($f1,"400");
            $f2_row_c_15 = $this->f2cRowNo($f2,"010") / $dv_f1cRowNo_15;

            $dv_f1eRowNo_15 = (0.00 == $this->f1eRowNo($f1,"400")) ? 1 : $this->f1eRowNo($f1,"400");
            $f2_row_e_15 = $this->f2eRowNo($f1,"010") / $dv_f1eRowNo_15;

            // row 17
            $dv_c_17 = ($this->f1cRowNo($f1,"770") - ($this->f1cRowNo($f1,"570") + $this->f1cRowNo($f1,"580") + $this->f1cRowNo($f1,"670")));
            $dv_f1cRowNo_17 = (0 == $dv_c_17) ? 1 : $dv_c_17;
            $f1_row_c_17 = ($this->f1cRowNo($f1,"480") + $this->f1cRowNo($f1,"570") + $this->f1cRowNo($f1,"580")) / $dv_f1cRowNo_17;

            $dv_e_17 = ($this->f1eRowNo($f1,"770") - ($this->f1eRowNo($f1,"570") + $this->f1eRowNo($f1,"580") + $this->f1eRowNo($f1,"670")));
            $dv_f1eRowNo_17 = (0 == $dv_e_17) ? 1 : $dv_e_17;
            $f1_row_e_17 = ($this->f1eRowNo($f1,"480") + $this->f1eRowNo($f1,"570") + $this->f1eRowNo($f1,"580")) / $dv_f1eRowNo_17;

            // row 18
            $dv_c_18 = ($this->f1cRowNo($f1,"780") - $this->f1cRowNo($f1,"022"));
            $dv_f1cRowNo_18 = (0 == $dv_c_18) ? 1 : $dv_c_18;
            $f1_row_c_18 = ($this->f1cRowNo($f1,"480") - $this->f1cRowNo($f1,"290")) / $dv_f1cRowNo_18 * 100;

            $dv_e_18 = ($this->f1eRowNo($f1,"780") - $this->f1eRowNo($f1,"022"));
            $dv_f1eRowNo_18 = (0 == $dv_e_18) ? 1 : $dv_e_18;
            $f1_row_e_18 = ($this->f1eRowNo($f1,"480") - $this->f1eRowNo($f1,"290")) / $dv_f1eRowNo_18 * 100;

            // row 21
            $dv_c_21 = (0.00 == $this->f2cRowNo($f2,"010")) ? 1 : $this->f2cRowNo($f2,"010");
            $f2_row_c_21 = $this->f2dRowNo($f2,"270") / $dv_c_21 * 100;

            $dv_e_21 = (0.00 == $this->f2eRowNo($f2,"010")) ? 1 : $this->f2eRowNo($f2,"010");
            $f2_row_e_21 = $this->f2fRowNo($f2,"270") / $dv_e_21 * 100;

            // row 22
            $dv_c_22 = (0.00 == $this->f2cRowNo($f2,"010")) ? 1 : $this->f2cRowNo($f2,"010");
            $f2_row_c_22 = $this->f2dRowNo($f2,"030") / $dv_c_22 * 100;

            $dv_e_22 = (0.00 == $this->f2eRowNo($f2,"010")) ? 1 : $this->f2eRowNo($f2,"010");
            $f2_row_e_22 = $this->f2eRowNo($f2,"030") / $dv_e_22 * 100;

            // row 23
            $dv_c_23 = (0.00 == $this->f1cRowNo($f1,"480")) ? 1 : $this->f1cRowNo($f1,"480");
            $f2_row_c_23 = $this->f2dRowNo($f2,"270") / $dv_c_23 * 100;

            $dv_e_23 = (0.00 == $this->f1eRowNo($f1,"480")) ? 1 : $this->f1eRowNo($f1,"480");
            $f2_row_e_23 = $this->f2fRowNo($f2,"270") / $dv_e_23 * 100;

            // row 24
            $dv_c_24 = ($this->f1cRowNo($f1,"012") + $this->f1cRowNo($f1,"022"));
            $dv_f1cRowNo_24 = (0 == $dv_c_24) ? 1 : $dv_c_24;
            $f2_row_c_24 = $this->f2cRowNo($f2,"270") / $dv_f1cRowNo_24 * 100;

            $dv_e_24 = ($this->f1eRowNo($f1,"012") + $this->f1eRowNo($f1,"022"));
            $dv_f1eRowNo_24 = (0 == $dv_e_24) ? 1 : $dv_e_24;
            $f2_row_e_24 = $this->f2eRowNo($f2,"270") / $dv_f1eRowNo_24 * 100;

            // row 25
            $dv_c_25 = (0.00 == $this->f1cRowNo($f1,"390")) ? 1 : $this->f1cRowNo($f1,"390");
            $f2_row_c_25 = $this->f2dRowNo($f2,"270") / $dv_c_25 * 100;

            $dv_e_25 = (0.00 == $this->f1eRowNo($f1,"390")) ? 1 : $this->f1eRowNo($f1,"390");
            $f2_row_e_25 = $this->f2fRowNo($f2,"270") / $dv_e_25 * 100;

            // row 26
            $dv_c_26 = (0.00 == $this->f1cRowNo($f1,"780")) ? 1 : $this->f1cRowNo($f1,"780");
            $f2_row_c_26 = $this->f2dRowNo($f2,"270") / $dv_c_26;

            $dv_e_26 = (0.00 == $this->f1eRowNo($f1,"780")) ? 1 : $this->f1eRowNo($f1,"780");
            $f2_row_e_26 = $this->f2fRowNo($f2,"270") / $dv_e_26;

            // row 36
            $dv_c_36 = (0.00 == $this->f2cRowNo($f2,"010")) ? 1 : $this->f2cRowNo($f2,"010");
            $f1_row_c_36 = $this->f1cRowNo($f1,"210") / $dv_c_36 * 100;

            $dv_e_36 = (0.00 == $this->f2eRowNo($f2,"010")) ? 1 : $this->f2eRowNo($f2,"010");
            $f1_row_e_36 = $this->f1eRowNo($f1,"210") / $dv_e_36 * 100;

            // row 37
            $dv_c_37 = (0.00 == $this->f2cRowNo($f2,"010")) ? 1 : $this->f2cRowNo($f2,"010");
            $f1_row_c_37 = $this->f1cRowNo($f1,"320") / $dv_c_37 * 100;

            $dv_e_37 = (0.00 == $this->f2eRowNo($f2,"010")) ? 1 : $this->f2eRowNo($f2,"010");
            $f1_row_e_37 = $this->f1eRowNo($f1,"320") / $dv_e_37 * 100;

            // row 38
            $dv_c_38 = ($this->f1cRowNo($f1,"010") + $this->f1cRowNo($f1,"020"));
            $dv_f1cRowNo_38 = (0.00 == $dv_c_38) ? 1 : $dv_c_38;
            $f1_row_c_38 = ($this->f1cRowNo($f1,"011") + $this->f1cRowNo($f1,"021")) / $dv_f1cRowNo_38;

            $dv_e_38 = ($this->f1eRowNo($f1,"010") + $this->f1eRowNo($f1,"020"));
            $dv_f1eRowNo_38 = (0.00 == $dv_e_38) ? 1 : $dv_e_38;
            $f1_row_e_38 = ($this->f1eRowNo($f1,"011") + $this->f1eRowNo($f1,"021")) / $dv_f1eRowNo_38;

            $res_table.='
                         <tr>
                            <td class="text-bold text-center">ПОКАЗАТЕЛИ</td>
                            <td class="text-bold">F1</td>
                            <td class="text-bold">F2</td>
                         </tr>
                         <tr>
                            <td>Рабочий капитал (Наличие СОС)</td>
                            <td>'.number_format($f1_row_c_5, 2).'</td><td>'.number_format($f1_row_e_5, 2).'</td>
                         </tr>
                         <tr>
                            <td>Коэффициент ликвидности</td>
                            <td>'.number_format($f1_row_c_6, 5).'</td><td>'.number_format($f1_row_e_6, 5).'</td>
                         </tr>
                         <tr>
                            <td>Коэффициент покрытия</td>
                            <td>'.number_format($f1_row_c_7, 5).'</td><td>'.number_format($f1_row_e_7, 5).'</td>
                         </tr>
                         <tr>
                            <td colspan="3" class="text-bold text-center bg-warning">ПОКАЗАТЕЛИ ДЕЛОВОЙ АКТИВНОСТИ</td>
                         </tr>
                         <tr>
                            <td>Оборачиваемость дебиторской задолженности (в днях)</td>
                            <td>'.number_format($f1_row_c_9).'</td><td>'.number_format($f1_row_e_9).'</td>
                         </tr>
                         <tr>
                            <td>Оборачиваемость кредиторской задолженности (в днях)</td>
                            <td>'.number_format($f1_row_c_10).'</td><td>'.number_format($f1_row_e_10).'</td>
                         </tr>
                         <tr>
                            <td>Оборачиваемость запасов готовой продукции  (в днях)</td>
                            <td>'.$f1_row_c_11.'</td><td>'.$f1_row_e_11.'</td>
                         </tr>
                         <tr>
                            <td>Оборачиваемость товаров для перепродажи  (в днях)</td>
                            <td>'.$f1_row_c_12.'</td><td>'.$f1_row_e_12.'</td>
                         </tr>
                         <tr>
                            <td>Оборачиваемость производственных запасов (в днях)</td>
                            <td>'.$f1_row_c_13.'</td><td>'.$f1_row_e_13.'</td>
                         </tr>
                         <tr>
                            <td>Оборачиваемость основных средств (в днях)</td>
                            <td>'.$f1_row_c_14.'</td><td>'.$f1_row_e_14.'</td>
                         </tr>
                         <tr>
                            <td>Оборачиваемость активов (в разах)</td>
                            <td>'.number_format($f2_row_c_15).'</td><td>'.$f2_row_e_15.'</td>
                         </tr>
                         <tr>
                            <td colspan="3" class="text-bold text-center bg-warning">ПОКАЗАТЕЛИ ДЕЛОВОЙ АКТИВНОСТИ</td>
                         </tr>
                         <tr>
                            <td>Коэффициент соотношения заемных и собственных средств</td>
                            <td>'.number_format($f1_row_c_17, 2).'</td><td>'.number_format($f1_row_e_17, 2).'</td>
                         </tr>
                         <tr>
                            <td>Коэффициент автономии</td>
                            <td>'.number_format($f1_row_c_18, 2).'%</td><td>'.number_format($f1_row_e_18, 2).'%</td>
                         </tr>
                         <tr>
                            <td>Коэффициент обслуживания долга</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td colspan="3" class="text-bold text-center bg-warning">ПОКАЗАТЕЛИ РЕНТАБЕЛЬНОСТИ</td>
                         </tr>
                         <tr>
                            <td>Коэффициент чистой рентабельности продаж</td>
                            <td>'.number_format($f2_row_c_21, 2).'%</td><td>'.number_format($f2_row_e_21, 2).'%</td>
                         </tr>
                         <tr>
                            <td>Коэффициент валовой рентабельности продаж </td>
                            <td>'.number_format($f2_row_c_22).'%</td><td>'.number_format($f2_row_e_22).'%</td>
                         </tr>
                         <tr>
                            <td>Коэффициент рентабельности собственного капитала</td>
                            <td>'.number_format($f2_row_c_23).'%</td><td>'.number_format($f2_row_e_23).'%</td>
                         </tr>
                         <tr>
                            <td>Коэффициент рентабельности основных средств</td>
                            <td>'.$f2_row_c_24.'%</td><td>'.$f2_row_e_24.'%</td>
                         </tr>
                         <tr>
                            <td>Коэффициент рентабельности оборотных активов</td>
                            <td>'.number_format($f2_row_c_25).'%</td><td>'.number_format($f2_row_e_25).'%</td>
                         </tr>
                         <tr>
                            <td>Коэффициент рентабельности  активов</td>
                            <td>'.number_format($f2_row_c_26, 2).'</td><td>'.number_format($f2_row_e_26, 2).'</td>
                         </tr>
                         <tr>
                            <td colspan="3" class="text-bold text-center bg-warning">ПОКАЗАТЕЛИ РЫНОЧНОЙ АКТИВНОСТИ</td>
                         </tr>
                         <tr>
                            <td>Прибыль на 1 акцию</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td>Соотношение рыночной цены акции и прибыли на 1 акцию</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td>Балансовая стоимость 1 акции</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td>Соотношение рыночной и балансовой стоимости 1 акции</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td>Доходность акций с учетом курсовой стоимости акции</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td>Норма дивиденда на 1 акцию</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td>Доля выплаченных дивидендов</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td colspan="3" class="text-bold text-center bg-warning"></td>
                         </tr>
                         <tr>
                            <td>Дебиторская задолженность / Чистые продажи</td>
                            <td>'.number_format($f1_row_c_36).'%</td><td>'.number_format($f1_row_e_36).'%</td>
                         </tr>
                         <tr>
                            <td>Денежные средства / Чистые продажи</td>
                            <td>'.number_format($f1_row_c_37).'%</td><td>'.number_format($f1_row_e_37).'%</td>
                         </tr>
                         <tr>
                            <td>Амортизация / Основные средства</td>
                            <td>'.$f1_row_c_38.'</td><td>'.$f1_row_e_38.'</td>
                         </tr>
                         <tr>
                            <td>Инвестиции в долгосрочные активы</td>
                            <td> - </td><td> - </td>
                         </tr>
                         <tr>
                            <td colspan="3" class="text-bold text-center bg-warning">: : : : : : : : : : : : : : : : : : : : : : : :: : : : : : : :</td>
                         </tr>';
                            // row 42
                            if ($f1_row_c_6 > 1.5){
                                $f1_row_c_42 = 1;
                            } elseif ($f1_row_c_6 <= 1.5 and $f1_row_c_6 > 1) {
                                $f1_row_c_42 = 2;
                            } elseif ($f1_row_c_6 <= 1 and $f1_row_c_6 > 0.5) {
                                $f1_row_c_42 = 3;
                            } elseif ($f1_row_c_6 <= 0.5) {
                                $f1_row_c_42 = 4;
                            } else {
                                $f1_row_c_42 = '-';
                            }

                            if ($f1_row_e_6 > 1.5){
                                $f1_row_e_42 = 1;
                            } elseif ($f1_row_e_6 <= 1.5 and $f1_row_e_6 > 1) {
                                $f1_row_e_42 = 2;
                            } elseif ($f1_row_e_6 <= 1 and $f1_row_e_6 > 0.5) {
                                $f1_row_e_42 = 3;
                            } elseif ($f1_row_e_6 <= 0.5) {
                                $f1_row_e_42 = 4;
                            } else {
                                $f1_row_e_42 = '-';
                            }

                            //row 43
                            if ($f1_row_c_7 > 2){
                                $f1_row_c_43 = 1;
                            } elseif ($f1_row_c_7 <= 2 and $f1_row_c_7 > 1) {
                                $f1_row_c_43 = 2;
                            } elseif ($f1_row_c_7 <= 1 and $f1_row_c_7 > 0.5) {
                                $f1_row_c_43 = 3;
                            } elseif ($f1_row_c_7 <= 0.5) {
                                $f1_row_c_43 = 4;
                            } else {
                                $f1_row_c_43 = '-';
                            }

                            if ($f1_row_e_7 > 2){
                                $f1_row_e_43 = 1;
                            } elseif ($f1_row_e_7 <= 2 and $f1_row_e_7 > 1) {
                                $f1_row_e_43 = 2;
                            } elseif ($f1_row_e_7 <= 1 and $f1_row_e_7 > 0.5) {
                                $f1_row_e_43 = 3;
                            } elseif ($f1_row_e_7 <= 0.5) {
                                $f1_row_e_43 = 4;
                            } else {
                                $f1_row_e_43 = '-';
                            }

                            //row 44
                            $f1_row_c_18 = $f1_row_c_18/100;
                            if ($f1_row_c_18 > 0.6){
                                $f1_row_c_44 = 1;
                            } elseif ($f1_row_c_18 <= 0.6 and $f1_row_c_18 > 0.3) {
                                $f1_row_c_44 = 2;
                            } elseif ($f1_row_c_18 <= 0.3 and $f1_row_c_18 > 0.15) {
                                $f1_row_c_44 = 3;
                            } elseif ($f1_row_c_18 <= 15) {
                                $f1_row_c_44 = 4;
                            } else {
                                $f1_row_c_44 = '-';
                            }

                            $f1_row_e_18 = $f1_row_e_18/100;
                            if ($f1_row_e_18 > 0.6){
                                $f1_row_e_44 = 1;
                            } elseif ($f1_row_e_18 <= 0.6 and $f1_row_e_18 > 0.3) {
                                $f1_row_e_44 = 2;
                            } elseif ($f1_row_e_18 <= 0.3 and $f1_row_e_18 > 0.15) {
                                $f1_row_e_44 = 3;
                            } elseif ($f1_row_e_18 <= 15) {
                                $f1_row_e_44 = 4;
                            } else {
                                $f1_row_e_44 = '-';
                            }
                            //print_r($f1_row_e_18); die;

                            //row 45
                            $f1_row_c_45 = sqrt( pow((1-$f1_row_c_42), 2) + pow((1-$f1_row_c_43),2) + pow((1-$f1_row_c_44),2));
                            $f1_row_e_45 = sqrt( pow((1-$f1_row_e_42), 2) + pow((1-$f1_row_e_43),2) + pow((1-$f1_row_e_44),2));
                            //print_r($f1_row_e_45); die;

                            //row 46
                            if ($f1_row_c_45 >= 0 and $f1_row_c_45 < 1.3){
                                $f1_row_c_46 = 'I';
                            } elseif ($f1_row_c_45 > 1.31 and $f1_row_c_45 < 2.6) {
                                $f1_row_c_46 = 'II';
                            } elseif ($f1_row_c_45 > 2.61 and $f1_row_c_45 < 3.9) {
                                $f1_row_c_46 = 'III';
                            } elseif ($f1_row_c_45 > 3.91 and $f1_row_c_45 < 5.2) {
                                $f1_row_c_46 = 'IV';
                            } else {
                                $f1_row_c_46 = '-';
                            }

                            if ($f1_row_e_45 >= 0 and $f1_row_e_45 < 1.3){
                                $f1_row_e_46 = 'I';
                            } elseif ($f1_row_e_45 > 1.31 and $f1_row_e_45 < 2.6) {
                                $f1_row_e_46 = 'II';
                            } elseif ($f1_row_e_45 > 2.61 and $f1_row_e_45 < 3.9) {
                                $f1_row_e_46 = 'III';
                            } elseif ($f1_row_e_45 > 3.91 and $f1_row_e_45 < 5.2) {
                                $f1_row_e_46 = 'IV';
                            } else {
                                $f1_row_e_46 = '-';
                            }

            $res_table.='<tr>
                            <td>Ликвидность</td>
                            <td>'.$f1_row_c_42.'</td><td>'.$f1_row_e_42.'</td>
                         <tr>
                            <td>Покрытия</td>
                            <td>'.$f1_row_c_43.'</td><td>'.$f1_row_e_43.'</td>
                         </tr>
                         <tr>
                            <td>Автономия</td>
                            <td>'.$f1_row_c_44.'</td><td>'.$f1_row_e_44.'</td>
                         </tr>
                         <tr>
                            <td class="text-bold">Расчеты</td>
                            <td>'.number_format($f1_row_c_45, 2).'</td><td>'.number_format($f1_row_e_45, 2).'</td>
                         </tr>
                         <tr>
                            <td class="text-bold">Итоговый класс</td>
                            <td>'.$f1_row_c_46.'</td><td>'.$f1_row_e_46.'</td>
                         </tr>
                         ';

        }

        return $res_table;
    }


}
