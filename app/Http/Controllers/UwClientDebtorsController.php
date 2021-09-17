<?php

namespace App\Http\Controllers;
use App\MWorkUsers;
use App\UwClientComments;
use App\UwClientCredits;
use App\UwClientDebtors;
use App\UwClients;
use App\UwLoanTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // dd($request->all());
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
                'salary'  =>$request->salary,
                'type'  =>$request->type,
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
                ->addColumn('action', function($data) use ($disabled,$model) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit edit-debtor '.$disabled.'">
<span class="glyphicon glyphicon-pencil"></span></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= ' | <a href="javascript:void(0);" id="delete-debtor'.$data->id.'" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete-debtor text-maroon  '.$disabled.'">
 <span class="glyphicon glyphicon-trash"></span></a>';
                    $button .= ' | <a href="javascript:void(0);" id="reg-debtor'.$data->id.'" data-toggle="tooltip" data-original-title="Register" data-id="'.$data->id.'" data-fullname="'.$data->family_name.' '.$data->name.' '.$data->patronymic.'" class="register-debtor text-green  '.$disabled.'">
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



}
