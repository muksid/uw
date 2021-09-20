<?php

namespace App\Http\Controllers;
use App\MWorkUsers;
use App\UwClientComments;
use App\UwClientDebComments;
use App\UwClientDebtors;
use App\UwClients;
use App\UwInpsClients;
use App\UwInpsDebClients;
use App\UwKatmClients;
use App\UwKatmDebClients;
use App\UwLoanTypes;
use App\UwPhyInpsBaseDebFile;
use App\UwPhyKatmBaseDebFile;
use App\UwPhyKatmDebFile;
use App\UwPhyKatmFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UwClientDebtorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $currentWorkUser = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->firstOrFail();

        if (!$currentWorkUser){
            return response()->json([
                'status' => 'warning',
                'message' => 'Inspektor passive holatda!!! (ip:247)'
            ]);
        }

        $id = $request->debtor_id;

        $branchCode = $currentWorkUser->branch_code;

        $lastModelId = UwClientDebtors::where('branch_code', $branchCode)->latest()->first();

        $claim_number = $lastModelId->claim_number + 1;

        $claim_id = '3'.$branchCode.$claim_number;

        $model = UwClientDebtors::updateOrCreate(['id' => $id],
            [
                'uw_clients_id' => $request->model_id,
                'inn' => $request->inn,
                'resident' => $request->resident,
                'document_type' => $request->document_type,
                'document_serial' => $request->document_serial,
                'document_number' => $request->document_number,
                'document_date' => $request->document_date,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'document_region' => $request->document_region,
                'document_district' => $request->document_district,
                'family_name' => $request->family_name,
                'name' => $request->name,
                'patronymic' => $request->patronymic,
                'pin' => $request->pin,
                'live_address' => $request->live_address,
                'job_address' => $request->job_address,
                'total_sum' => $request->total_sum,
                'total_month' => $request->total_month,
                'claim_id'=>$claim_id,
                'claim_number'=>$claim_number,
                'branch_code'=>$lastModelId->branch_code,
                'has_salary'    => $request->has_salary,
                'deb_type'    => $request->deb_type,
                'isActive' => 1,
            ]);
        return response()->json([
            'success' => true,
            'result' => $model
        ]);
    }

    public function getDebtorClient($id)
    {

        return $this->onlineDebtorsRegistration($id);
    }

    public function onlineDebtorsRegistration($id)
    {
        //
        $modelClient = UwClientDebtors::find($id);

        $model = UwClients::find($modelClient->uw_clients_id);


        if ($modelClient->isReg == 1) {

            return $this->creditReportK($id, $modelClient->claim_id, $modelClient->is_inps);

        }

      $url = 'http://10.22.50.3:8003/katm-api/v1/credit/registration/pledge/owner';

      $data = array(
        "security" => array(
            "pLogin" => "turonbank",
            "pPassword" => "!trB&GkL@200130"
        ),
        "data" => array(
        "pHead" => "011",
        "pCode" => "".$modelClient->branch_code."",
        "pClaimId" => "".$modelClient->claim_id."",
        "pContractId" => "".$modelClient->claim_id."",
        "pInn" => "".$modelClient->inn."",
        "pNibbd" => "".$modelClient->claim_number."",
        "pAgreementDate" => "".date("Y-m-d ", strtotime($modelClient->created_at))."",
        "pAgreementNumber" => "".$modelClient->claim_number."",
        "pClientId" => "".$model->katm_sir."", // katm sir
        "pClientType" => "08",
        "pDate" => "".date("Y-m-d ", strtotime($modelClient->created_at))."",
        "pDateBirthday" => "".date("Y-m-d ", strtotime($modelClient->birth_date))."",
        "pFio"=> "".$modelClient->family_name." ".$modelClient->name."",
        "pFullName" => "".$modelClient->family_name." ".$modelClient->name." ".$modelClient->patronymic."",
        "pIdentityCardDate" => "".date("Y-m-d ", strtotime($modelClient->document_date))."",
        "pIdentityCardNumber" => "".$modelClient->document_number."",
        "pIdenticalCardSerial" => "".$modelClient->document_serial."",
        "pIdentityCardType" => "",
        "pLegalAddress"=>"",
        "pOwnerId"=>"".$modelClient->claim_id."",
        "pPersonalCode" => "".$modelClient->pin."", //pnfl
        "pResident" => "1",
        "pSex" => "".$modelClient->gender."",
        "pSubjectType"=>"2" //document type
        ),
      );



      $postdata = json_encode($data);
    //   print($postdata);
        //print_r($postdata); die;

        $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8003');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $json_data = curl_exec($ch1);
        curl_close($ch1);
      //print_r($json_data); die;

      $data_decode = json_decode($json_data, true);

      $code = $data_decode['result']['code'];

      $message = $data_decode['result']['message'];

      $pClientId = $data_decode['response']['pClientId']; //katm sir

      $clientComment = new UwClientComments();
      $clientComment->uw_clients_id = $id;
      $clientComment->work_user_id = Auth::id();
      $clientComment->claim_id = $modelClient->claim_id;
      $clientComment->title = '(code:'.$code.') Online Registration Success';
      $clientComment->comment_type = '1';
      $clientComment->katm_sir = $pClientId; //katm sir
      $clientComment->katm_type = 1;
      $clientComment->katm_descr = $json_data;
      $clientComment->save();

      if ($code == '05000') {

          // update reg
          $modelClient = UwClientDebtors::find($id);

          $modelClient->update(['isReg' => 1]);

          return $this->creditReportK($id, $modelClient->claim_id, $modelClient->is_inps);

      } else {
          return response()->json(
              [
                  'status'=>'warning',
                  'message'=>'('.$code.') KATM ro`yhatga olishda xatolik mavjud!',
                  'data'=> $data_decode,

                ]);
      }

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show($id)
    {
        //
        if(request()->ajax())
        {
            $model = UwClients::find($id);
            $disabled = '';
            if ($model->status == 2 || $model->status == 3){
                $disabled = 'btn disabled';
            }

            return datatables()->of(UwClientDebtors::where('uw_clients_id', $id)->get())
                ->addColumn('action', function($data) use ($disabled) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit edit-debtor '.$disabled.'">
                    <span class="glyphicon glyphicon-pencil"></span></a>';
                                        $button .= '&nbsp;&nbsp;';
                                        $button .= ' | <a href="javascript:void(0);" id="delete-debtor" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete text-maroon  '.$disabled.'">
                    <span class="glyphicon glyphicon-trash"></span></a>';
                                        $button .= ' | <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Register" data-id="'.$data->id.'" class="text-green reg-debtor">
                    <span class="glyphicon glyphicon-globe"></span></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

    }





    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function edit($id)
    {
        //
        $model = UwClientDebtors::find($id);

        return response()->json($model);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        $debtor = UwClientDebtors::find($id);
        $debtor->delete();

        return response()->json([
            'message' => 'Data deleted successfully!'
        ]);
    }


    public function postKatm(Request $request)
    {
        $model_id = $request->debtor_id;

        $post_type = $request->post_type;

        if ($post_type == 'reg') {

            return $this->onlineRegistration($model_id);

        } elseif ($post_type == 'decline') {

            return $this->clientDecline($model_id);

        } elseif ($post_type == 'scoring') {

            return $this->getCreditScoring($model_id);

        } elseif ($post_type == 'history') {

            return $this->getCreditHistory($model_id);

        } elseif ($post_type == 'salary_tin') {

            return $this->getClientSalaryTin($model_id);

        } elseif ($post_type == 'salary_xb') {

            return $this->getClientSalaryXB($model_id);

        } elseif ($post_type == 'mib') {

            return $this->getClientMib($model_id);

        } elseif ($post_type == 'get_credit_history') {

            $credit_history = UwPhyKatmFile::where('uw_clients_id', $model_id)->where('uw_katm_id', 0)->where('file_type', '=', 'B64_K_HIS')->first();

            $data = '';
            if ($credit_history){

                $path = $credit_history->file_path.$credit_history->file_hash;

                if (Storage::disk('ftp_nas')->exists($path)){

                    $data = Storage::disk('ftp_nas')->get($path);

                    $data = base64_decode($data);

                }

            }

            return response()->json(
                [
                    'message' => 'KATM Kredit tarixi natijasi',
                    'data' => $data,
                    'type' => 'get_credit_history'
                ]);

        } else {

            return response()->json(['message' => 'Error post type!!!']);

        }

    }

    public function curlInit($arrData)
    {
        //
        $base_url = '10.22.50.3';

        $port = $arrData['port'];

        $path = $arrData['path'];

        $post_data = $arrData['post_data'];

        $ch = curl_init('http://'.$base_url.':'.$port.'/'.$path);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, $base_url.':'.$port);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);

        return $this->curlResult($arrData, $result);
    }

    public function curlResult($arrData, $result)
    {

        if ($arrData['type'] == 'reg') {

            return $this->curlResultReg($arrData, $result);

        } elseif ($arrData['type'] == 'decline') {

            return $this->curlResultDecline($arrData, $result);

        } elseif ($arrData['type'] == 'scoring') {

            return $this->curlResultScoring($arrData, $result);

        } elseif ($arrData['type'] == 'scoring_status') {

            return $this->curlResultScoringStatus($arrData, $result);

        } elseif ($arrData['type'] == 'history') {

            return $this->curlResultHistory($arrData, $result);

        } elseif ($arrData['type'] == 'history_status') {

            return $this->curlResultHistoryStatus($arrData, $result);

        } elseif ($arrData['type'] == 'salary_xb') {

            return $this->curlResultSalaryXB($arrData, $result);

        } elseif ($arrData['type'] == 'salary_xb_status') {

            return $this->curlResultSalaryXBStatus($arrData, $result);

        } elseif ($arrData['type'] == 'salary_tin') {

            return $this->curlResultSalaryTin($arrData, $result);

        } elseif ($arrData['type'] == 'mib') {

            return $this->curlResultMib($arrData, $result);

        } else {

            return response()->json(
                [
                    'message'=>'Error code'
                ]);

        }

    }

    public function onlineRegistration($model_id)
    {
        //
        $modelClient = UwClientDebtors::find($model_id);

        $uw_client = UwClients::find($modelClient->uw_clients_id);

        $modelLoanType = UwLoanTypes::find($uw_client->loan_type_id);

        if ($modelClient->isReg == 1) {

            $checkScoring = UwKatmDebClients::where('uw_deb_id', $model_id)->first();
            if ($checkScoring) {

                $checkSalary = UwInpsDebClients::where('uw_deb_id', $model_id)->first();

                if ($checkSalary) {
                    return response()->json(
                        [
                            'message'=>'KATM Scoring KIAS va Oylik daromadi muvaffaqiyatli saqlandi'
                        ]);
                } else {

                    $checkHasSalary = UwClientDebtors::find($model_id);
                    if ($checkHasSalary->has_salary == 'Y') {

                        return $this->getClientSalaryXB($model_id);
                    } else {
                        return response()->json(
                            [
                                'message'=>'KATM Scoring KIAS muvaffaqiyatli saqlandi',
                            ]);
                    }
                }

            }

            return $this->getCreditScoring($model_id);

            /*return response()->json(['message' => 'Mijoz KATM dasturida ro`yhatdan o`tgan!!!']);*/

        }

        $data = array(
            "header" => array(
                "type" => "B",
                "code" => "".$modelClient->branch_code.""
            ),
            "request" => array(
                "claim_id" => "".$modelClient->claim_id."",
                "claim_date" => "".date("d.m.Y", strtotime($uw_client->claim_date))."",
                "inn" => "".$modelClient->inn."",
                "claim_number" => "".$modelClient->claim_number."",
                "agreement_number" => "".$modelClient->claim_number."",
                "agreement_date" => "".date("d.m.Y", strtotime($uw_client->claim_date))."",
                "resident" => "1",
                "document_type" => "6",
                "document_serial" => "".$modelClient->document_serial."",
                "document_number" => "".$modelClient->document_number."",
                "document_date" => "".date("d.m.Y", strtotime($modelClient->document_date))."",
                "gender" => "".$modelClient->gender."",
                "client_type" => "08",
                "birth_date" => "".date("d.m.Y", strtotime($modelClient->birth_date))."",
                "document_region" => "".$modelClient->document_region."",
                "document_district" => "".$modelClient->document_district."",
                "nibbd" => "",
                "family_name" => "".$modelClient->family_name."",
                "name" => "".$modelClient->name."",
                "patronymic" => "".$modelClient->patronymic."",
                "registration_region" => "".$modelClient->document_region."",
                "registration_district" => "".$modelClient->document_district."",
                "registration_address" => "".$modelClient->live_address."",
                "phone" => "",
                "pin" => "".$modelClient->pin."",
                "katm_sir" => "",
                "credit_type" => "".$modelLoanType->credit_type."",
                "summa" => "".$uw_client->summa."",
                "procent" => "".$modelLoanType->procent."",
                "credit_duration" => "".$modelLoanType->credit_duration."",
                "credit_exemtion" => "".$modelLoanType->credit_exemtion."",
                "currency" => "".$modelLoanType->currency."",
                "live_address" => "".$modelClient->live_address."",
                "live_cadastr" => "",
                "registration_cadastr" => ""
            ),
        );
        $postData = json_encode($data);

        $arrData = array(
            'port' => '8000',
            'path' => 'inquiry/individual',
            'type' => 'reg',
            'model_id' => $model_id,
            'claim_id' => $modelClient->claim_id,
            'branch_code' => $modelClient->branch_code,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function getCreditScoring($model_id)
    {
        //
        $modelClient = UwClientDebtors::find($model_id);

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$modelClient->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$modelClient->claim_id."",
                "pReportId" => "021",
                "pReportFormat" => 1
            ),
        );

        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/credit/report',
            'type' => 'scoring',
            'model_id' => $model_id,
            'claim_id' => $modelClient->claim_id,
            'branch_code' => $modelClient->branch_code,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function getClientScoringStatus($arrData, $token){

        //
        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$arrData['branch_code']."",
                "pToken" => "".$token."",
                "pLegal" => 1,
                "pClaimId" => "".$arrData['claim_id']."",
                "pReportId" => "021",
                "pReportFormat" => 1
            ),
        );

        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/credit/report/status',
            'type' => 'scoring_status',
            'model_id' => $arrData['model_id'],
            'claim_id' => $arrData['claim_id'],
            'branch_code' => $arrData['branch_code'],
            'token' => $token,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function getCreditHistory($model_id)
    {
        //
        $modelClient = UwClients::find($model_id);

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$modelClient->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$modelClient->claim_id."",
                "pReportId" => "1",
                "pReportFormat" => 0
            ),
        );

        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/credit/report',
            'type' => 'history',
            'model_id' => $model_id,
            'claim_id' => $modelClient->claim_id,
            'branch_code' => $modelClient->branch_code,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function getClientHistoryStatus($arrData, $token)
    {
        //
        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$arrData['branch_code']."",
                "pToken" => "".$token."",
                "pLegal" => 1,
                "pClaimId" => "".$arrData['claim_id']."",
                "pReportId" => "1",
                "pReportFormat" => 0
            ),
        );

        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/credit/report/status',
            'type' => 'history_status',
            'model_id' => $arrData['model_id'],
            'claim_id' => $arrData['claim_id'],
            'branch_code' => $arrData['branch_code'],
            'token' => $token,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);


    }

    public function getClientSalaryTin($model_id)
    {
        //
        $modelClient = UwClients::find($model_id);

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$modelClient->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$modelClient->claim_id."",
                "pReportId" => "048",
                "pReportFormat" => 1
            ),
        );

        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/credit/report',
            'type' => 'salary_tin',
            'model_id' => $model_id,
            'claim_id' => $modelClient->claim_id,
            'branch_code' => $modelClient->branch_code,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function getClientSalaryXB($model_id)
    {
        //
        $modelClient = UwClientDebtors::find($model_id);

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$modelClient->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$modelClient->claim_id."",
                "pReportId" => 25,
                "pReportFormat" => 1
            ),
        );

        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/credit/report',
            'type' => 'salary_xb',
            'model_id' => $model_id,
            'claim_id' => $modelClient->claim_id,
            'branch_code' => $modelClient->branch_code,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function getClientSalaryXBStatus($arrData, $token){
        //
        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$arrData['branch_code']."",
                "pToken" => "".$token."",
                "pLegal" => 1,
                "pClaimId" => "".$arrData['claim_id']."",
                "pReportId" => "025",
                "pReportFormat" => 1
            ),
        );

        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/credit/report/status',
            'type' => 'salary_xb_status',
            'model_id' => $arrData['model_id'],
            'claim_id' => $arrData['claim_id'],
            'branch_code' => $arrData['branch_code'],
            'token' => $token,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function getClientMib($model_id){
        //
        $modelClient = UwClients::find($model_id);

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$modelClient->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$modelClient->claim_id."",
                "pReportId" => "039",
                "pReportFormat" => 1
            ),
        );

        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/credit/report',
            'type' => 'mib',
            'model_id' => $model_id,
            'claim_id' => $modelClient->claim_id,
            'branch_code' => $modelClient->branch_code,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function clientDecline($model_id)
    {
        //
        $modelClient = UwClients::find($model_id);

        $isCheckKatm = UwKatmClients::where('uw_clients_id', $model_id)->first();

        if (!$isCheckKatm){

            return response()->json(['message' => 'Mijoz KATM dasturida ro`yhatga olinmagan!!!']);

        }

        $date = date("Y-m-d").'T'.date("H:s:i");

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$modelClient->branch_code."",
                "pDeclineDate" => "".$date."",
                "pClaimId" => "".$modelClient->claim_id."",
                "pDeclineNumber" => "".$modelClient->claim_number."",
                "pDeclineReason" => "1",
                "pDeclineReasonNote" => "Ariza bekor qildi!!!"
            ),
        );
        $postData = json_encode($data);

        $arrData = array(
            'port' => '8001',
            'path' => 'katm-api/v1/claim/decline',
            'type' => 'decline',
            'model_id' => $model_id,
            'claim_id' => $modelClient->claim_id,
            'branch_code' => $modelClient->branch_code,
            'post_data' =>$postData
        );

        return $this->curlInit($arrData);

    }

    public function curlResultReg($arrData, $result)
    {
        //
        $data_decode = json_decode($result, true);

        $code = $data_decode['result']['code'];

        $message = $data_decode['result']['message'];

        $katm_sir = $data_decode['response']['katm_sir'];

        if ($code == '05000') {
            $model_id = $arrData['model_id'];

            $claim_id = $arrData['claim_id'];

            $clientComment = new UwClientDebComments();
            $clientComment->uw_deb_id = $model_id;
            $clientComment->claim_id = $claim_id;
            $clientComment->code = $code;
            $clientComment->work_user_id = Auth::user()->currentWork->id??0;
            $clientComment->katm_sir = $katm_sir;
            $clientComment->json_data = $result;
            $clientComment->title = $message.' - Online registratsiya muvaffaqiyatli bajarildi';
            $clientComment->process_type = 'R';
            $clientComment->save();

            // update reg
            $modelClient = UwClientDebtors::find($model_id);
            $modelClient->update(['isReg' => 1, 'status' => 1, 'katm_sir' => $katm_sir]);

            return $this->getCreditScoring($model_id);
            /*return response()->json(
                [
                    'message'=>'('.$code.') Online registratsiya muvaffaqiyatli bajarildi',
                    'type'=> $arrData['type'],
                    'data'=> $data_decode
                ]);*/

        } else {
            return response()->json(
                [
                    'message'=>'('.$code.') KATM ro`yhatga olishda xatolik mavjud!',
                    'type'=> $arrData['type'],
                    'data'=> $data_decode,
                ]);
        }

    }

    public function curlResultDecline($arrData, $result)
    {
        //
        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];

        $result = $data_decode['data']['result'];

        $resultMessage = $data_decode['data']['resultMessage'];

        if ($code == '200') {

            if ($result == '05000') {

                $model_id = $arrData['model_id'];

                $claim_id = $arrData['claim_id'];

                $clientComment = new UwClientComments();
                $clientComment->uw_clients_id = $model_id;
                $clientComment->work_user_id = Auth::user()->currentWork->id??0;
                $clientComment->claim_id = $claim_id;
                $clientComment->code = $result;
                $clientComment->title = $resultMessage.' Mijoz KATM dasturi ro`yhatidan o`chirildi.';
                $clientComment->comment_type = '-1';
                $clientComment->process_type = 'DEL';
                $clientComment->katm_type = 0;
                $clientComment->json_data = '';
                $clientComment->save();

                // update reg
                $modelClient = UwClients::find($model_id);
                $modelClient->update(['status' => -1, 'reg_status' => 0, 'katm_sir' => '']);

                return response()->json(
                    [
                        'message'=>'('.$result.') Mijoz KATM ro`yhatidan o`chirildi',
                        'type'=> $arrData['type'],
                        'data'=> $data_decode
                    ]);
            }

            return response()->json(
                [
                    'message'=>'('.$result.') KATM ro`yhatidan o`chirishda xatolik mavjud!!!',
                    'type'=> $arrData['type'],
                    'data'=> $data_decode,
                ]);

        } else {
            return response()->json(
                [
                    'message'=>'('.$code.') KATM ro`yhatidan o`chirishda xatolik mavjud!!!',
                    'type'=> $arrData['type'],
                    'data'=> $data_decode,
                ]);
        }

    }

    public function curlResultScoring($arrData, $result)
    {
        //
        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];
        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($code == '200'){
            if ($result == '05050'){

                $token = $data_decode['data']['token'];

                return $this->getClientScoringStatus($arrData, $token);

            } else {
                return response()->json(['message' => $resultMessage]);
            }

        } else {

            $errorMessage = $data_decode['errorMessage'];

            return response()->json(['message' => 'code: '.$code.' '.$errorMessage, 'type' => 'salary_xb']);
        }

    }

    public function curlResultScoringStatus($arrData, $result)
    {
        //
        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];

        if ($code == '200') {

            $result_code = $data_decode['data']['result'];

            if ($result_code == '05000') {

                $reportBase64 = $data_decode['data']['reportBase64'];

                $base64_decode = base64_decode($reportBase64);

                if ($base64_decode) {

                    $model_id = $arrData['model_id'];

                    $claim_id = $arrData['claim_id'];

                    $today = Carbon::today();
                    $year = $today->year;
                    $month = $today->month;
                    $day = $today->day;

                    $json_decode_arr = json_decode($base64_decode, true);

                    $base_arr = $json_decode_arr['html']['body']['div'][1]['table'];
                    $arr1 = $base_arr[1]['tbody']['tr'];
                    $arr2 = $base_arr[2]['tbody']['tr'];
                    $arr_merge = array_merge($arr1,$arr2);
                    $txt_base64 = base64_encode(json_encode($arr_merge)); // for save all data base64

                    $hash_filename = md5(time().$claim_id);

                    $path_txt = 'uw/phy/deb/kias/'.$year.'/'.$month.'/'.$day.'/'.$hash_filename.'.txt';

                    Storage::disk('ftp_nas')->put($path_txt, $txt_base64);

                    /*$img_scoring_ball = $arr1[10]['td'][1]['img']['src']; // save img scoring ball

                    if (preg_match('/^data:image\/(\w+);base64,/', $img_scoring_ball)) {
                        $data = substr($img_scoring_ball, strpos($img_scoring_ball, ',') + 1);

                        $data = base64_decode($data);

                        $path_img = 'uw/phy/img/'.$year.'/'.$month.'/'.$day.'/'.$hash_filename.'.png';

                        Storage::disk('ftp_nas')->put($path_img, $data);
                    }*/

                    $scoring_ball = $arr1[10]['td'][1]['div']['span']; // scoring ball

                    $arr_summa = $arr1[31]['td'][2]['span'][0]; // exit summa

                    if (count($arr_summa) > 1) {
                        # code...
                        $summa = $arr1[31]['td'][2]['span'][0]['span'];
                        $summa = preg_replace('/[^0-9]/', '', $summa);
                    } else{
                        $summa = 0;
                    }

                    // json_data table
                    for ($i=17; $i < 32; $i++) {
                        // code...
                        $arr_table[$i] = array_merge($arr1[$i]);

                    }
                    $jon_data = base64_encode(json_encode($arr_table)); // table

                    $katm = UwKatmDebClients::updateOrCreate(['uw_deb_id' => $model_id],
                        [
                            'uw_deb_id' => $model_id,
                            'claim_id' => $claim_id,
                            'summa' => $summa,
                            'scoring_ball' => $scoring_ball,
                            'json_data' => $jon_data,
                            'isVersion' => 2,
                            'status' => 1
                        ]);

                    $clientComment = new UwClientDebComments();
                    $clientComment->uw_deb_id = $model_id;
                    $clientComment->claim_id = $claim_id;
                    $clientComment->work_user_id = Auth::user()->currentWork->id??0;
                    $clientComment->code = $result_code;
                    $clientComment->title = 'KATM Scoring KIAS muvaffaqiyatli saqlandi';
                    $clientComment->json_data = '';
                    $clientComment->process_type = 'KS';
                    $clientComment->save();

                    // save katm base file
                    $katmBaseFile = new UwPhyKatmBaseDebFile();
                    $katmBaseFile->uw_deb_id = $model_id;
                    $katmBaseFile->uw_katm_id = $katm->id;
                    $katmBaseFile->base_file = $txt_base64;
                    $katmBaseFile->save();

                    // save katm file (base64 txt)
                    $katmFile = new UwPhyKatmDebFile();
                    $katmFile->uw_deb_id = $model_id;
                    $katmFile->uw_katm_id = $katm->id;
                    $katmFile->file_path = 'uw/phy/deb/kias/'.$year.'/'.$month.'/'.$day.'/';
                    $katmFile->file_hash = $hash_filename.'.txt';
                    $katmFile->file_type = 'B64';
                    $katmFile->save();

                    // save katm file (scoring ball png)
                    /*$katmFile = new UwPhyKatmFile();
                    $katmFile->uw_clients_id = $id;
                    $katmFile->uw_katm_id = $katm->id;
                    $katmFile->file_path = 'uw/phy/img/'.$year.'/'.$month.'/'.$day.'/';
                    $katmFile->file_hash = $hash_filename.'.png';
                    $katmFile->file_type = 'IMG';
                    $katmFile->save();*/

                    $checkSalary = UwClientDebtors::find($model_id);
                    if ($checkSalary->has_salary == 'Y') {

                        return $this->getClientSalaryXB($model_id);
                    } else {
                        return response()->json(
                            [
                                'message'=>'KATM Scoring KIAS muvaffaqiyatli saqlandi',
                                'type'=> $arrData['type']
                            ]);
                    }

                    /*return response()->json(
                        [
                            'message'=>'KATM Scoring KIAS muvaffaqiyatli saqlandi',
                            'type'=> $arrData['type']
                        ]);*/


                } else {

                    $token = $arrData['token'];
                    return $this->getClientScoringStatus($arrData, $token);
                }
            }

            $token = $arrData['token'];
            return $this->getClientScoringStatus($arrData, $token);

        } else {

            $message = $data_decode['errorMessage'];

            return response()->json(['message' => '('.$code.')'.$message]);
        }

    }

    public function curlResultHistory($arrData, $result)
    {
        //
        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];
        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($code == '200'){
            if ($result == '05050'){

                $token = $data_decode['data']['token'];

                return $this->getClientHistoryStatus($arrData, $token);

            } else {
                return response()->json(['message' => $resultMessage]);
            }

        } else {

            $errorMessage = $data_decode['errorMessage'];

            return response()->json(['message' => 'code: '.$code.' '.$errorMessage, 'type' => 'history']);
        }

    }

    public function curlResultHistoryStatus($arrData, $result)
    {
        //
        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];

        if ($code == '200') {

            $result_code = $data_decode['data']['result'];

            if ($result_code == '05000') {

                $reportBase64 = $data_decode['data']['reportBase64'];

                if ($reportBase64) {

                    $model_id = $arrData['model_id'];

                    $claim_id = $arrData['claim_id'];

                    $today = Carbon::today();
                    $year = $today->year;
                    $month = $today->month;
                    $day = $today->day;

                    $hash_filename = md5(time().$claim_id);

                    $path_txt = 'uw/phy/kias_his/'.$year.'/'.$month.'/'.$day.'/'.$hash_filename.'.txt';

                    Storage::disk('ftp_nas')->put($path_txt, $reportBase64);

                    $clientComment = new UwClientComments();
                    $clientComment->uw_clients_id = $model_id;
                    $clientComment->claim_id = $claim_id;
                    $clientComment->work_user_id = Auth::user()->currentWork->id??0;
                    $clientComment->code = $result_code;
                    $clientComment->title = 'KATM Kredit tarixi muvaffaqiyatli saqlandi';
                    $clientComment->json_data = '';
                    $clientComment->process_type = 'K_HIS';
                    $clientComment->save();

                    // save katm file (base64 txt)
                    $katmFile = new UwPhyKatmFile();
                    $katmFile->uw_clients_id = $model_id;
                    $katmFile->uw_katm_id = 0;
                    $katmFile->file_path = 'uw/phy/kias_his/'.$year.'/'.$month.'/'.$day.'/';
                    $katmFile->file_hash = $hash_filename.'.txt';
                    $katmFile->file_type = 'B64_K_HIS';
                    $katmFile->save();

                    return response()->json(
                        [
                            'message'=>'KATM Kredit tarixi muvaffaqiyatli saqlandi',
                            'type'=> $arrData['type']
                        ]);


                } else {

                    $token = $arrData['token'];
                    return $this->getClientHistoryStatus($arrData, $token);
                }
            }

            $token = $arrData['token'];
            return $this->getClientHistoryStatus($arrData, $token);

        } else {

            $message = $data_decode['errorMessage'];

            return response()->json(['message' => '('.$code.')'.$message]);
        }

    }

    public function curlResultSalaryXB($arrData, $result)
    {
        //
        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];
        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($code == '200'){
            if ($result == '05050'){

                $token = $data_decode['data']['token'];

                return $this->getClientSalaryXBStatus($arrData, $token);

            } else {
                return response()->json(['message' => $resultMessage]);
            }

        } else {

            $errorMessage = $data_decode['errorMessage'];

            return response()->json(['message' => 'code: '.$code.' '.$errorMessage, 'type' => 'salary_xb']);
        }

    }

    public function curlResultSalaryXBStatus($arrData, $result)
    {
        //
        $data_decode = json_decode($result, true);

        $result = $data_decode['data']['result'];

        $reportBase64 = $data_decode['data']['reportBase64'];

        $code = $data_decode['code'];

        if ($code == '200') {

            if ($result == '05000') {
                $model_id = $arrData['model_id'];

                $claim_id = $arrData['claim_id'];

                if ($reportBase64) {
                    $clientComment = new UwClientDebComments();
                    $clientComment->uw_deb_id = $model_id;
                    $clientComment->claim_id = $claim_id;
                    $clientComment->work_user_id = Auth::user()->currentWork->id??0;
                    $clientComment->code = $result;
                    $clientComment->title = 'Oylik daromadi ma`lumotlari muvaffaqiyatli saqlandi. (XB)';
                    $clientComment->json_data = '';
                    $clientComment->process_type = 'SAL_X';
                    $clientComment->save();

                    $base64_decode = base64_decode($reportBase64);
                    $array = json_decode($base64_decode, true);
                    //print_r($array); die;
                    $array_client = $array['report']['client'];
                    $array_incomes_period = $array['report']['incomes_period'];
                    $array_sysinfo = $array['report']['sysinfo'];
                    $array_presence_reports = $array['report']['presence_reports'];
                    $array_notifications = $array['report']['notifications'];

                    /*$array_merge = array_merge([
                        'client' => $array_client,
                        'incomes_period' => $array_incomes_period,
                        'sysinfo' => $array_sysinfo,
                        'presence_reports' => $array_presence_reports,
                        'array_notifications' => $array_notifications
                    ]);
                    $base_json = json_encode($array_merge);
                    $base_file = base64_decode($base_json);
                    // base64 save
                    $baseFile = new UwPhyInpsBaseDebFile();
                    $baseFile->uw_deb_id = $model_id;
                    $baseFile->base_file = $base_file;
                    $baseFile->save();*/

                    if ($array['report']['incomes']) {


                        $old_salary = UwInpsDebClients::where('uw_deb_id', $model_id);
                        $old_salary->delete();

                        $array_income = $array['report']['incomes']['INCOME'];

                        if (array_filter($array_income, 'is_array')) {

                            foreach ($array_income as $key => $value) {
                                $inps = new UwInpsDebClients();

                                $year = substr($value['PERIOD'], 0,4);
                                $month = substr($value['PERIOD'], 5,6);

                                $inps->uw_deb_id = $model_id;
                                $inps->claim_id = $claim_id;
                                $inps->client_name = $array_client['name'];
                                $inps->client_tin = $array_client['inn'];
                                $inps->pinfl = $array_client['pinfl'];
                                $inps->ns10_code = 0;
                                $inps->ns11_code = 0;
                                $inps->ORG_INN = $value['ORG_INN'];
                                $inps->INCOME_SUMMA = $value['INCOME_SUMMA'];
                                $inps->salary_tax_sum = 0;
                                $inps->series_passport = $array_client['document_serial'];
                                $inps->number_passport = $array_client['document_number'];
                                $inps->NUM = $month;
                                $inps->PERIOD = $year;
                                $inps->ORGNAME = $value['ORGNAME'];
                                $inps->status = 1;
                                $inps->isVersion = 1;
                                $inps->save();
                            }

                        } else {

                            $inps = new UwInpsDebClients();

                            $year = substr($array_income['PERIOD'], 0,4);
                            $month = substr($array_income['PERIOD'], 5,6);

                            $inps->uw_deb_id = $model_id;
                            $inps->claim_id = $claim_id;
                            $inps->client_name = $array_client['name'];
                            $inps->client_tin = $array_client['inn'];
                            $inps->pinfl = $array_client['pinfl'];
                            $inps->ns10_code = 0;
                            $inps->ns11_code = 0;
                            $inps->ORG_INN = $array_income['ORG_INN'];
                            $inps->INCOME_SUMMA = $array_income['INCOME_SUMMA'];
                            $inps->salary_tax_sum = 0;
                            $inps->series_passport = $array_client['document_serial'];
                            $inps->number_passport = $array_client['document_number'];
                            $inps->NUM = $month;
                            $inps->PERIOD = $year;
                            $inps->ORGNAME = $array_income['ORGNAME'];
                            $inps->status = 1;
                            $inps->isVersion = 1;
                            $inps->save();
                        }

                    } else {

                        $inps = new UwInpsDebClients();
                        $inps->uw_deb_id = $model_id;
                        $inps->claim_id = $claim_id;
                        $inps->client_name = $array_client['name'];
                        $inps->client_tin = $array_client['inn'];
                        $inps->pinfl = $array_client['pinfl'];
                        $inps->ns10_code = 0;
                        $inps->ns11_code = 0;
                        $inps->ORG_INN = '';
                        $inps->INCOME_SUMMA = 0;
                        $inps->salary_tax_sum = 0;
                        $inps->series_passport = $array_client['document_serial'];
                        $inps->number_passport = $array_client['document_number'];
                        $inps->NUM = date('m');
                        $inps->PERIOD = date('Y');
                        $inps->ORGNAME = '';
                        $inps->status = 1;
                        $inps->isVersion = 1;
                        $inps->save();

                    }

                    return response()->json(
                        [
                            'message'=>'('.$result.') Mijoz Oylik daromadi saqlandi (XALQBANK)',
                            'type'=> $arrData['type']
                        ]);

                } else {
                    $token = $arrData['token'];
                    return $this->getClientSalaryXBStatus($arrData, $token);
                }

            }

            $token = $arrData['token'];
            return $this->getClientSalaryXBStatus($arrData, $token);

        } else {

            $message = $data_decode['errorMessage'];

            return response()->json(['message' => '('.$code.')'.$message]);
        }
    }

    public function curlResultSalaryTin($arrData, $result)
    {
        $result_decode = json_decode($result, true);

        $code = $result_decode['code'];

        if ($code == '200') {

            $result_code = $result_decode['data']['result'];

            if ($result_code == '05000') {

                $model_id = $arrData['model_id'];

                $claim_id = $arrData['claim_id'];

                $clientComment = new UwClientComments();
                $clientComment->uw_clients_id = $model_id;
                $clientComment->claim_id = $claim_id;
                $clientComment->work_user_id = Auth::user()->currentWork->id??0;
                $clientComment->code = $result_code;
                $clientComment->title = 'Oylik ish haqi ma`lumotlari muvaffaqiyatli saqlandi (SOLIQ)';
                $clientComment->json_data = '';
                $clientComment->process_type = 'SAL';
                $clientComment->save();


                $message = $result_decode['data']['resultMessage'];
                $resultBase64 = $result_decode['data']['reportBase64'];
                $base64_decode = base64_decode($resultBase64);
                $models_decode = json_decode($base64_decode, true);
                $models_status = $models_decode['report']['success'];

                if ($models_status == 1) {

                    $models = $models_decode['report']['data'];

                    $old_inps = UwInpsClients::where('uw_clients_id', $model_id)->where('status', 1);
                    $old_inps->delete();

                    if (array_filter($models, 'is_array')) {
                        # code...

                        $today = Carbon::today();
                        $year = $today->year - 1;
                        $month = $today->month;

                        foreach ($models as $key => $value) {
                            # code...
                            $inps = new UwInpsClients();
                            if ($value['year'] >= $year && ($value['period'] >= $month || $value['year'] > $year)){

                                $inps->uw_clients_id = $model_id;
                                $inps->claim_id = $claim_id;
                                $inps->client_tin = $value['tin'];
                                $inps->client_name = $value['name'];
                                $inps->PERIOD = $value['year'];
                                $inps->NUM = $value['period'];
                                $inps->pinfl = $value['pinfl'];
                                $inps->ns10_code = $value['ns10_code'];
                                $inps->ns11_code = $value['ns11_code'];
                                $inps->ORGNAME = $value['company_name'];
                                $inps->ORG_INN = $value['company_tin'];
                                $inps->INCOME_SUMMA = $value['salary'];
                                $inps->salary_tax_sum = $value['salary_tax_sum'];
                                $inps->inps_sum = $value['inps_sum'];
                                $inps->prop_income = $value['prop_income'];
                                $inps->other_income = $value['other_income'];
                                $inps->series_passport = $value['series_passport'];
                                $inps->number_passport = $value['number_passport'];
                                $inps->isVersion = 2;
                                $inps->status = 1;
                                $inps->save();
                            } else {
                                return response()->json(
                                    [
                                        'message' => 'Oxirgi 12 oy davomida oylik daromadi topilmadi!!!'
                                    ]);
                            }
                        }

                        return response()->json(
                            [
                                'message' => '('.$message.') Oylik daromadi ma`lumotlari muvaffaqiyatli saqlandi. (SOLIQ)'
                            ]);

                    }
                }


            } else {
                $message = $result_decode['data']['resultMessage'];

                return response()->json(['message' => '('.$code.')'.$message]);

            }
        }
        else {

            $message = $result_decode['errorMessage'];

            $code = $result_decode['code'];

            return response()->json(['message' => '('.$code.')'.$message]);
        }
    }

    public function curlResultMib($arrData, $result)
    {
        $result_decode = json_decode($result, true);

        $code = $result_decode['code'];

        if ($code == '200') {

            $result_code = $result_decode['data']['result'];

            if ($result_code == '05000') {

                $model_id = $arrData['model_id'];

                $claim_id = $arrData['claim_id'];

                $clientComment = new UwClientComments();
                $clientComment->uw_clients_id = $model_id;
                $clientComment->claim_id = $claim_id;
                $clientComment->work_user_id = Auth::user()->currentWork->id??0;
                $clientComment->code = $result_code;
                $clientComment->title = 'Mijoz qarzdorliklari muvaffaqiyatli olindi (MIB)';
                $clientComment->json_data = '';
                $clientComment->process_type = 'MIB';
                $clientComment->save();


                $message = $result_decode['data']['resultMessage'];
                $resultBase64 = $result_decode['data']['reportBase64'];
                $base64_decode = base64_decode($resultBase64);
                $models_decode = json_decode($base64_decode, true);

                $models_decode = $models_decode['report'];
                $allDebtSum = $models_decode['allDebtSum'];
                $debts = $models_decode['debts'];
                $passportNumber = $models_decode['passportNumber'];
                $passportSn = $models_decode['passportSn'];
                $reviewDate = $models_decode['reviewDate'];

                return response()->json([
                    'message' => '('.$result_code.')'.$message,
                    'type'=> $arrData['type'],
                    'allDebtSum' => $allDebtSum,
                    'debts' => $debts,
                    'passportNumber' => $passportNumber,
                    'passportSn' => $passportSn,
                    'reviewDate' => $reviewDate
                ]);

            } else {
                $message = $result_decode['data']['resultMessage'];

                return response()->json(['message' => '('.$code.')'.$message]);

            }
        }
        else {

            $message = $result_decode['errorMessage'];

            $code = $result_decode['code'];

            return response()->json(['message' => '('.$code.')'.$message]);
        }
    }

    function ftp_file_exists(){

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

    public function getDebtorScoring(Request $request)
    {
        $id = $request->id;

        $scoring_k = UwKatmDebClients::where('uw_deb_id', $id)->where('status', 1)->first();
        if(!$scoring_k){
            return [];
        }else{
            $scoringBase64 = UwPhyKatmBaseDebFile::where('uw_katm_id', $scoring_k->id)->first();
    
            if (!$scoringBase64) {
                return [];
            }
    
            $katmBase64 = UwPhyKatmDebFile::where('uw_deb_id', $id)->where('file_type', 'B64')->orderBy('id', 'desc')->first();
    
            $scoring_file = '';
            $scoring_img = '';
            if ($katmBase64){
                $path = $katmBase64->file_path.$katmBase64->file_hash;
    
                if ($scoring_k->isVersion == 1) {
    
                    $scoring_img = Storage::disk('ftp_nas')->get($path.".php");
    
                }
    
                if (Storage::disk('ftp_nas')->exists($path)){
    
                    $data = Storage::disk('ftp_nas')->get($path);
    
                    $base64 = base64_decode($data);
    
                    $scoring_file = json_decode($base64, true);
    
                }
    
            }
    
            $clientModel = UwClientDebtors::find($id);
    
            $scoringPage = file_get_contents("uw/scoring_page.php");
    
            return response()->json([
                'client_model'  => $clientModel,
                'scoring_k'  => $scoring_k,
                'scoring_base64'  => $scoringBase64,
                'scoring_file'  => $scoring_file,
                'scoring_page'  => $scoringPage,
                'scoring_img'  => $scoring_img,
            ]);
        }
    }

    public function getDebtorSalary(Request $request)
    {
        $id = $request->id;
        $result = UwInpsDebClients::where('uw_deb_id', $id)->where('status', 1)->get();

        if ($result->count() < 0) {
            return response()->json([
                'message' => 'error'
            ]);
        }
        return response()->json($result, 200);

    }



}
