<?php

namespace App\Http\Controllers;

use App\MWorkUsers;
use App\UwJurBalanceChild;
use App\UwJurBalanceForm;
use App\UwJurClientComment;
use App\UwJurClientPersonal;
use App\UwJurFinancialChild;
use App\UwJurFinancialForm;
use App\UwJuridicalClient;
use App\UwJurKatmClient;
use App\UwJurKatmFile;
use App\UwLoanTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UwInquiryEntityController extends Controller
{
    //
    public $URL_INQ_IND  = 'http://10.22.50.3:8000/inquiry/individual';
    public $URL_INQ_ENT  = 'http://10.22.50.3:8000/inquiry/entity';
    public $URL_CREDIT_REPORT  = 'http://10.22.50.3:8001/katm-api/v1/credit/report';
    public $URL_CREDIT_REPORT_STATUS  = 'http://10.22.50.3:8001/katm-api/v1/credit/report/status';

    public function curlHttpPost($array)
    {
        $url = $array['url'];
        $post = $array['data'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);

        //print_r($result); die;

        return $result;

    }

    public function onlineRegistration(Request $request)
    {
        //
        $id = $request->id;

        $modelClient = UwJuridicalClient::find($id);

        $modelLoanType = UwLoanTypes::find($modelClient->loan_type_id);

        if ($modelClient->katm_sir != '') {

            return response()->json(
                [
                    'status'=>'warning',
                    'message'=>'Mijoz KATM dasturida ro`yhatdan o`tgan'
                ]);

        }

        if ($modelClient->client_type == '11'){
            $url = $this->URL_INQ_IND;

            $personal = UwJurClientPersonal::where('jur_clients_id', $id)->first();

            $data = array(
                "header" => array(
                    "type" => "B",
                    "code" => "".$modelClient->branch_code.""
                ),
                "request" => array(
                    "claim_id" => "".$modelClient->claim_id."",
                    "claim_date" => "".date("d.m.Y", strtotime($modelClient->claim_date))."",
                    "inn" => "",
                    "claim_number" => "".$modelClient->claim_number."",
                    "agreement_number" => "".$modelClient->claim_number."",
                    "agreement_date" => "".date("d.m.Y", strtotime($modelClient->claim_date))."",
                    "resident" => "".$personal->resident."",
                    "document_type" => "".$personal->document_type."",
                    "document_serial" => "".$personal->document_serial."",
                    "document_number" => "".$personal->document_number."",
                    "document_date" => "".date("d.m.Y", strtotime($personal->document_date))."",
                    "gender" => "".$personal->gender."",
                    "client_type" => "".$personal->client_type."",
                    "birth_date" => "".date("d.m.Y", strtotime($personal->birth_date))."",
                    "document_region" => "".$personal->document_region."",
                    "document_district" => "".$personal->document_district."",
                    "nibbd" => "",
                    "family_name" => "".$personal->family_name."",
                    "name" => "".$personal->name."",
                    "patronymic" => "".$personal->patronymic."",
                    "registration_region" => "".$personal->registration_region."",
                    "registration_district" => "".$personal->registration_district."",
                    "registration_address" => "".$personal->registration_address."",
                    "phone" => "".$modelClient->phone."",
                    "pin" => "".$personal->pin."",
                    "katm_sir" => "",
                    "credit_type" => "".$modelLoanType->credit_type."",
                    "summa" => "".$modelClient->summa."",
                    "procent" => "".$modelLoanType->procent."",
                    "credit_duration" => "".$modelLoanType->credit_duration."",
                    "credit_exemtion" => "".$modelLoanType->credit_exemtion."",
                    "currency" => "".$modelLoanType->currency."",
                    "live_address" => "".$personal->live_address."",
                    "live_cadastr" => "",
                    "registration_cadastr" => ""
                ),
            );

        } else {

            $url = $this->URL_INQ_ENT;

            $data = array(
                "header" => array(
                    "type" => "B",
                    "code" => "".$modelClient->branch_code.""
                ),
                "request" => array(
                    "claim_id" => "".$modelClient->claim_id."",
                    "claim_date" => "".date("d.m.Y", strtotime($modelClient->claim_date))."",
                    "inn" => "".$modelClient->inn."",
                    "claim_number" => "".$modelClient->claim_number."",
                    "agreement_number" => "".$modelClient->claim_number."",
                    "agreement_date" => "".date("d.m.Y", strtotime($modelClient->claim_date))."",
                    "resident" => "".$modelClient->resident."",
                    "juridical_status" => "1",
                    "nibbd" => "".$modelClient->client_code."",
                    "client_type" => "".$modelClient->client_type."",
                    "name" => "".$modelClient->jur_name."",
                    "owner_form" => "".$modelClient->owner_form."",
                    "goverment" => "".$modelClient->goverment."",
                    "registration_region" => "".$modelClient->registration_region."",
                    "registration_district" => "".$modelClient->registration_district."",
                    "registration_address" => "".$modelClient->registration_address."",
                    "phone" => "".$modelClient->phone."",
                    "hbranch" => "",
                    "oked" => "".$modelClient->oked."",
                    "katm_sir" => "".$modelClient->katm_sir."",
                    "okpo" => "".$modelClient->code_juridical_person."",// code_juridical_person
                    "credit_type" => "".$modelLoanType->credit_type."",
                    "summa" => "".$modelClient->summa."",
                    "procent" => "".$modelLoanType->procent."",
                    "credit_duration" => "".$modelLoanType->credit_duration."",
                    "credit_exemtion" => "".$modelLoanType->credit_exemtion."",
                    "currency" => "".$modelLoanType->currency."",
                    "live_cadastr" => "",
                    "registration_cadastr" => ""
                ),
            );

        }

        $postData = json_encode($data);

        /*$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '10.22.50.3:8000');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);*/
        $arr_curl = array(
            'url' => $url,
            'data' => $postData
        );
        $result = $this->curlHttpPost($arr_curl);

        $data_decode = json_decode($result, true);
        $code = $data_decode['result']['code'];
        $message = $data_decode['result']['message'];
        $katm_sir = $data_decode['response']['katm_sir'];

        if ($code == '05000') {

            $clientComment = new UwJurClientComment();
            $clientComment->jur_clients_id = $id;
            $clientComment->code = $code;
            $clientComment->work_user_id = Auth::user()->currentWork->id??0;
            $clientComment->json_data = $result;
            $clientComment->title = $message;
            $clientComment->process_type = 'R';
            $clientComment->save();

            // update reg
            $modelClient = UwJuridicalClient::find($id);

            $modelClient->update(['katm_sir' => $katm_sir]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Mijoz KATM dasturiga muvaffaqiyatli ro`yhatga olindi'
                ]);

        } else {
            return response()->json(
                [
                    'status'=>'warning',
                    'code'=>$code,
                    'message'=>'KATM ro`yhatga olishda xatolik mavjud! code: ('.$code.') '.$message.''
                ]);
        }

    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditReportScoring(Request $request)
    {
        //
        $id = $request->id;

        $isKATM = UwJurKatmClient::where('jur_clients_id', $id)->where('status', 1)->first();

        if ($isKATM){

            $isKATM->update(['status' => 0]);

            /*return response()->json([
                    'success' => 'Mijoz KATM Scoring ma`lumotlari yangilandi!',
                    'code' => '05000',
                    'data' => $isKATM
                ]);*/
        }

        $model = UwJuridicalClient::find($id);

        $url = 'http://10.22.50.3:8001/katm-api/v1/credit/report';

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$model->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$model->claim_id."",
                "pReportId" => 21,
                "pReportFormat" => 1
            ),
        );

        $postData = json_encode($data);

        /*$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);*/

        $arr_curl = array(
            'url' => $url,
            'data' => $postData
        );
        $result = $this->curlHttpPost($arr_curl);

        $data_decode = json_decode($result, true);

        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($result == '05050'){

            $token = $data_decode['data']['token'];

            return $this->creditReportStatusK($id, $model->branch_code, $token, $model->claim_id, $model->client_type);

        } else {
            $errorMessage = $data_decode['errorMessage'];
            return response()->json(
                [
                    'status'=>'warning',
                    'message'=>'('.$result.') KATM Mijoz kredit tarixini olishda xatolik mavjud!',
                    'data'=> $errorMessage
                ]);
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

    /**
     * Display a listing of the resource.
     *
     * * @param  int  $token
     * * @param  int  $id
     * * @param  int  $branch_code
     * * @param  int  $claim_id
     * * @param  int  $client_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditReportStatusK($id, $branch_code, $token, $claim_id, $client_type)
    {
        //
        //$url = 'http://10.22.50.3:8001/katm-api/v1/credit/report/status';

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$branch_code."",
                "pToken" => "".$token."",
                "pLegal" => 1,
                "pClaimId" => "".$claim_id."",
                "pReportId" => 21,
                "pReportFormat" => 1
            ),
        );

        $postData = json_encode($data);

        /*$ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $json_data = curl_exec($ch1);
        curl_close($ch1);*/

        $arr_curl = array(
            'url' => $this->URL_CREDIT_REPORT_STATUS,
            'data' => $postData
        );
        $json_data = $this->curlHttpPost($arr_curl);

        $json_data_decode = json_decode($json_data, true);

        $base64_decode = base64_decode($json_data_decode['data']['reportBase64']);

        if ($this->ftp_file_exists() == 0){
            return response()->json(
                [
                    'status'=>'error',
                    'message'=>'FTP SERVER ga ulanishda muammo bor!!! (ip:153)'
                ]);
        }

        if ($base64_decode){
            $code = $json_data_decode['code'];

            if ($code == 200) {
                $result_code = $json_data_decode['data']['result'];

                if ($result_code == '05000') {
                    $today = Carbon::today();
                    $year = $today->year;
                    $month = $today->month;
                    $day = $today->day;

                    $base64_decode = base64_decode($json_data_decode['data']['reportBase64']);
                    $json_decode_arr = json_decode($base64_decode, true);
                    $base_arr = $json_decode_arr['html']['body']['div'][1]['table'];
                    $arr1 = $base_arr[1]['tbody']['tr'];
                    $arr2 = $base_arr[2]['tbody']['tr'];
                    $arr_merge = array_merge($arr1,$arr2);
                    $txt_base64 = base64_encode(json_encode($arr_merge)); // for save all data base64

                    $hash_filename = md5(time().$claim_id);

                    $path_txt = 'uw/jur/kias/'.$year.'/'.$month.'/'.$day.'/'.$hash_filename.'.txt';

                    Storage::disk('ftp_nas')->put($path_txt, $txt_base64);

                    /*$img_scoring_ball = $arr1[8]['td'][1]['img']['src']; // save img scoring ball

                    if (preg_match('/^data:image\/(\w+);base64,/', $img_scoring_ball)) {
                        $data = substr($img_scoring_ball, strpos($img_scoring_ball, ',') + 1);

                        $data = base64_decode($data);

                        $path_img = 'uw/jur/img/'.$year.'/'.$month.'/'.$day.'/'.$hash_filename.'.png';

                        Storage::disk('ftp_nas')->put($path_img, $data);
                    }*/

                    if ($client_type == '11'){
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

                    } else {
                        $scoring_ball = $arr1[8]['td'][1]['div']['span']; // scoring ball

                        $arr_summa = $arr1[28]['td'][2]['span'][0]; // exit summa

                        if (count($arr_summa) > 1) {
                            # code...
                            $summa = $arr1[28]['td'][2]['span'][0]['span'];
                            $summa = preg_replace('/[^0-9]/', '', $summa);
                        } else{
                            $summa = 0;
                        }

                        // json_data table
                        for ($i=15; $i < 29; $i++) {
                            // code...
                            $arr_table[$i] = array_merge($arr1[$i]);

                        }

                    }
                    $jon_data = base64_encode(json_encode($arr_table)); // table

                    $katm = UwJurKatmClient::updateOrCreate(['jur_clients_id' => $id],
                        [
                            'uw_clients_id' => $id,
                            'claim_id' => $claim_id,
                            'summa' => $summa,
                            'scoring_ball' => $scoring_ball,
                            'json_data' => $jon_data,
                            'status' => 1
                        ]);

                    $clientComment = new UwJurClientComment();
                    $clientComment->jur_clients_id = $id;
                    $clientComment->work_user_id = Auth::user()->currentWork->id??'0';
                    $clientComment->code = $result_code;
                    $clientComment->title = '(code:'.$code.') KATM Scoring KIAS muvaffaqiyatli saqlandi';
                    $clientComment->json_data = $json_data_decode['data']['resultMessage'];
                    $clientComment->process_type = 'KS';
                    $clientComment->save();

                    // save katm file (base64 txt)
                    $katmFile = new UwJurKatmFile();
                    $katmFile->jur_clients_id = $id;
                    $katmFile->jur_katm_id = $katm->id;
                    $katmFile->file_hash = $path_txt;
                    $katmFile->file_name = $hash_filename.'.txt';
                    $katmFile->file_type = 'B64';
                    $katmFile->save();

                    // save katm file (scoring ball png)
                    /*$katmFile = new UwJurKatmFile();
                    $katmFile->jur_clients_id = $id;
                    $katmFile->jur_katm_id = $katm->id;
                    $katmFile->file_hash = $path_img;
                    $katmFile->file_name = $hash_filename.'.png';
                    $katmFile->file_type = 'IMG';
                    $katmFile->save();*/

                    return response()->json(
                        [
                            'status'=>'success',
                            'code'=>'05000',
                            'message'=>'KATM Scoring KIAS muvaffaqiyatli saqlandi',
                            'data'=> $katm,
                            'json_data'=> $json_data
                        ]);
                }
            }

        } else{
            return $this->creditReportStatusK($id, $branch_code, $token, $claim_id, $client_type);
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalanceForm(Request $request)
    {
        $id = $request->id;

        $year = $request->year;

        $quarter = $request->quarter;

        $type = $request->type;
        if ($type == 'b'){

            $pReportId = '060';

            $modelB = UwJurBalanceForm::where('uw_jur_clients_id', $id)->where('isActive', 1)->first();

            if ($modelB) {

                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Balance form 1 natijasi muvaffaqiyatli saqlandi',
                        'data' => $modelB
                    ]);
            }

        } elseif ($type == 'fb') {

            $modelFB = UwJurFinancialForm::where('uw_jur_clients_id', $id)->where('isActive', 1)->first();

            $pReportId = '062';

            if ($modelFB) {

                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Moliyaviy Balance form 2 natijasi muvaffaqiyatli saqlandi',
                        'data' => $modelFB
                    ]);
            }

        }

        $client = UwJuridicalClient::find($id);

        //$base_url = 'http://10.22.50.3:8001/katm-api/v1/credit/report/';

        $postParam = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$client->branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$client->claim_id."",
                "pReportId" => "".$pReportId."",
                "pYear" => $year,
                "pQuarter" => $quarter,
                "pReportFormat" => 1,
            ),
        );

        $postParamEncode = json_encode($postParam);

        /*$ch1 = curl_init($base_url);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postParamEncode);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $json_data = curl_exec($ch1);
        curl_close($ch1);*/
        $arr_curl = array(
            'url' => $this->URL_CREDIT_REPORT,
            'data' => $postParamEncode
        );
        $json_data = $this->curlHttpPost($arr_curl);

        $json_data_decode = json_decode($json_data, true);

        $code = $json_data_decode['code'];
        if ($code == 200) {
            $result_code = $json_data_decode['data']['result'];

            if ($result_code == '05000') {
                $base64_decode = base64_decode($json_data_decode['data']['reportBase64']);
                $json_decode_arr = json_decode($base64_decode, true);
                $base_data = $json_decode_arr['report']['data'];

                $clientComment = new UwJurClientComment();
                $clientComment->jur_clients_id = $id;
                $clientComment->work_user_id = Auth::user()->currentWork->id??'0';
                $clientComment->code = $result_code;
                $clientComment->title = '(code:'.$code.') Moliyaviy hisobot muvaffaqiyatli saqlandi ('.$type.')';
                $clientComment->json_data = '';
                $clientComment->process_type = 'BAL';
                $clientComment->save();

                if ($pReportId == '060'){

                    $balance = new UwJurBalanceForm();
                    $balance->uw_jur_clients_id = $id;
                    $balance->year = $year;
                    $balance->quarter = $quarter;
                    $balance->ns10_code = $base_data['ns10_code'];
                    $balance->ns11_code = $base_data['ns11_code'];
                    $balance->tin = $base_data['tin'];
                    $balance->company_name = $base_data['company_name'];
                    $balance->isActive = 1;
                    $balance->save();

                    $base_arr = $json_decode_arr['report']['data']['rows'];

                    foreach ($base_arr as $value){
                        $child = new UwJurBalanceChild();
                        $child->uw_jur_balance_id = $balance->id;
                        $child->row_no = $value['row_no'];
                        $child->sum_begin_period = $value['sum_begin_period'];
                        $child->sum_end_period = $value['sum_end_period'];
                        $child->save();
                    }

                } elseif ($pReportId == '062'){

                    $balance = new UwJurFinancialForm();
                    $balance->uw_jur_clients_id = $id;
                    $balance->year = $year;
                    $balance->quarter = $quarter;
                    $balance->ns10_code = $base_data['ns10_code'];
                    $balance->ns11_code = $base_data['ns11_code'];
                    $balance->tin = $base_data['tin'];
                    $balance->company_name = $base_data['company_name'];
                    $balance->isActive = 1;
                    $balance->save();

                    $base_arr = $json_decode_arr['report']['data']['rows'];

                    foreach ($base_arr as $value){
                        $child = new UwJurFinancialChild();
                        $child->uw_jur_financial_id = $balance->id;
                        $child->row_no = $value['row_no'];
                        $child->sum_old_period_doxod = $value['sum_old_period_doxod'];
                        $child->sum_old_period_rasxod = $value['sum_old_period_rasxod'];
                        $child->sum_period_doxod = $value['sum_period_doxod'];
                        $child->sum_period_rasxod = $value['sum_period_rasxod'];
                        $child->save();
                    }

                }

                return response()->json($json_decode_arr);

            }
            return response()->json($json_data_decode);
        }

        return response()->json($json_data_decode);

    }

}
