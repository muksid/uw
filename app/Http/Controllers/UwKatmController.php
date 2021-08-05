<?php

namespace App\Http\Controllers;

use App\UwClientComments;
use App\UwClients;
use App\UwInpsClients;
use App\UwKatmClients;
use App\UwLoanTypes;
use App\UwPhyInpsBaseFile;
use App\UwPhyKatmFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UwKatmController extends Controller
{
    //
    public function postKatm(Request $request)
    {
        $model_id = $request->model_id;

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

        } else {

            return response()->json(['message' => 'Error post type!!!']);

        }

    }

    public function onlineRegistration($model_id)
    {
        //
        $modelClient = UwClients::find($model_id);

        $modelLoanType = UwLoanTypes::find($modelClient->loan_type_id);

        if ($modelClient->reg_status == 1) {

            return response()->json(['message' => 'Mijoz KATM dasturida ro`yhatdan o`tgan!!!']);

        }

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
                "registration_region" => "".$modelClient->registration_region."",
                "registration_district" => "".$modelClient->registration_district."",
                "registration_address" => "".$modelClient->registration_address."",
                "phone" => "".$modelClient->phone."",
                "pin" => "".$modelClient->pin."",
                "katm_sir" => "",
                "credit_type" => "".$modelLoanType->credit_type."",
                "summa" => "".$modelClient->summa."",
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
        $isKATM = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();

        if ($isKATM){

            return $this->getClientSalary($id, $claim_id, $branch_code);
        }

        $url = 'http://10.22.50.3:8001/katm-api/v1/credit/report';

        $data = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$branch_code."",
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
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);

        $data_decode = json_decode($result, true);
        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($result == '05050'){

            $token = $data_decode['data']['token'];

            return $this->creditReportStatusK($token, $id, $claim_id, $is_inps, $branch_code);

        } else {
            return response()->json(
                [
                    'status'=>'warning',
                    'message'=>'('.$result.') KATM Mijoz kredit tarixini olishda xatolik mavjud!',
                    'data'=> $resultMessage,
                    'credit_results' => $this->clientCreditResults($id),
                ]);
        }

    }

    public function getCreditScoringStatus($model_id, $token)
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
                "pCode" => "".$branch_code."",
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
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $json_data = curl_exec($ch1);
        curl_close($ch1);

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
            //print_r($code); die;
            if ($code == 200) {
                // code...
                $result_code = $json_data_decode['data']['result'];
                if ($result_code == '05000') {
                    // code...
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

                    $path_txt = 'uw/phy/kias/'.$year.'/'.$month.'/'.$day.'/'.$hash_filename.'.txt';

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

                    $katm = UwKatmClients::updateOrCreate(['uw_clients_id' => $id],
                        [
                            'uw_clients_id' => $id,
                            'claim_id' => $claim_id,
                            'summa' => $summa,
                            'scoring_ball' => $scoring_ball,
                            'json_data' => $jon_data,
                            'isVersion' => 2,
                            'status' => 1
                        ]);

                    $clientComment = new UwClientComments();
                    $clientComment->uw_clients_id = $id;
                    $clientComment->claim_id = $claim_id;
                    $clientComment->work_user_id = Auth::user()->currentWork->id??'0';
                    $clientComment->code = $result_code;
                    $clientComment->title = 'KATM Scoring KIAS muvaffaqiyatli saqlandi';
                    $clientComment->json_data = '';
                    $clientComment->process_type = 'KS';
                    $clientComment->save();

                    // save katm file (base64 txt)
                    $katmFile = new UwPhyKatmFile();
                    $katmFile->uw_clients_id = $id;
                    $katmFile->uw_katm_id = $katm->id;
                    $katmFile->file_path = 'uw/phy/kias/'.$year.'/'.$month.'/'.$day.'/';
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

                    if ($is_inps == 1 && $scoring_ball > 199){

                        return $this->getClientSalary($id, $claim_id, $branch_code);

                    } else {

                        return response()->json(
                            [
                                'status'=>'success',
                                'message'=>'KATM Scoring KIAS muvaffaqiyatli saqlandi',
                                'is_inps'=> 1,
                                'data'=> $katm,
                                'credit_results' => $this->clientCreditResults($id),
                            ]);
                    }
                }
            }

        } else{
            return $this->creditReportStatusK($token, $id, $claim_id, $is_inps, $branch_code);
        }


    }

    public function getCreditHistory($token, $id, $claim_id, $is_inps, $branch_code)
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
                "pCode" => "".$branch_code."",
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
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $json_data = curl_exec($ch1);
        curl_close($ch1);

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
            //print_r($code); die;
            if ($code == 200) {
                // code...
                $result_code = $json_data_decode['data']['result'];
                if ($result_code == '05000') {
                    // code...
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

                    $path_txt = 'uw/phy/kias/'.$year.'/'.$month.'/'.$day.'/'.$hash_filename.'.txt';

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

                    $katm = UwKatmClients::updateOrCreate(['uw_clients_id' => $id],
                        [
                            'uw_clients_id' => $id,
                            'claim_id' => $claim_id,
                            'summa' => $summa,
                            'scoring_ball' => $scoring_ball,
                            'json_data' => $jon_data,
                            'isVersion' => 2,
                            'status' => 1
                        ]);

                    $clientComment = new UwClientComments();
                    $clientComment->uw_clients_id = $id;
                    $clientComment->claim_id = $claim_id;
                    $clientComment->work_user_id = Auth::user()->currentWork->id??'0';
                    $clientComment->code = $result_code;
                    $clientComment->title = 'KATM Scoring KIAS muvaffaqiyatli saqlandi';
                    $clientComment->json_data = '';
                    $clientComment->process_type = 'KS';
                    $clientComment->save();

                    // save katm file (base64 txt)
                    $katmFile = new UwPhyKatmFile();
                    $katmFile->uw_clients_id = $id;
                    $katmFile->uw_katm_id = $katm->id;
                    $katmFile->file_path = 'uw/phy/kias/'.$year.'/'.$month.'/'.$day.'/';
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

                    if ($is_inps == 1 && $scoring_ball > 199){

                        return $this->getClientSalary($id, $claim_id, $branch_code);

                    } else {

                        return response()->json(
                            [
                                'status'=>'success',
                                'message'=>'KATM Scoring KIAS muvaffaqiyatli saqlandi',
                                'is_inps'=> 1,
                                'data'=> $katm,
                                'credit_results' => $this->clientCreditResults($id),
                            ]);
                    }
                }
            }

        } else{
            return $this->creditReportStatusK($token, $id, $claim_id, $is_inps, $branch_code);
        }


    }

    public function getCreditHistoryStatus($token, $id, $claim_id, $is_inps, $branch_code)
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
                "pCode" => "".$branch_code."",
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
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $json_data = curl_exec($ch1);
        curl_close($ch1);

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
            //print_r($code); die;
            if ($code == 200) {
                // code...
                $result_code = $json_data_decode['data']['result'];
                if ($result_code == '05000') {
                    // code...
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

                    $path_txt = 'uw/phy/kias/'.$year.'/'.$month.'/'.$day.'/'.$hash_filename.'.txt';

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

                    $katm = UwKatmClients::updateOrCreate(['uw_clients_id' => $id],
                        [
                            'uw_clients_id' => $id,
                            'claim_id' => $claim_id,
                            'summa' => $summa,
                            'scoring_ball' => $scoring_ball,
                            'json_data' => $jon_data,
                            'isVersion' => 2,
                            'status' => 1
                        ]);

                    $clientComment = new UwClientComments();
                    $clientComment->uw_clients_id = $id;
                    $clientComment->claim_id = $claim_id;
                    $clientComment->work_user_id = Auth::user()->currentWork->id??'0';
                    $clientComment->code = $result_code;
                    $clientComment->title = 'KATM Scoring KIAS muvaffaqiyatli saqlandi';
                    $clientComment->json_data = '';
                    $clientComment->process_type = 'KS';
                    $clientComment->save();

                    // save katm file (base64 txt)
                    $katmFile = new UwPhyKatmFile();
                    $katmFile->uw_clients_id = $id;
                    $katmFile->uw_katm_id = $katm->id;
                    $katmFile->file_path = 'uw/phy/kias/'.$year.'/'.$month.'/'.$day.'/';
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

                    if ($is_inps == 1 && $scoring_ball > 199){

                        return $this->getClientSalary($id, $claim_id, $branch_code);

                    } else {

                        return response()->json(
                            [
                                'status'=>'success',
                                'message'=>'KATM Scoring KIAS muvaffaqiyatli saqlandi',
                                'is_inps'=> 1,
                                'data'=> $katm,
                                'credit_results' => $this->clientCreditResults($id),
                            ]);
                    }
                }
            }

        } else{
            return $this->creditReportStatusK($token, $id, $claim_id, $is_inps, $branch_code);
        }


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
                "pReportId" => 25,
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

            $clientComment = new UwClientComments();
            $clientComment->uw_clients_id = $model_id;
            $clientComment->claim_id = $claim_id;
            $clientComment->code = $code;
            $clientComment->work_user_id = Auth::user()->currentWork->id??0;
            $clientComment->katm_sir = $katm_sir;
            $clientComment->json_data = $result;
            $clientComment->title = $message.' - Online registratsiya muvaffaqiyatli bajarildi';
            $clientComment->process_type = 'R';
            $clientComment->save();

            // update reg
            $modelClient = UwClients::find($model_id);
            $modelClient->update(['reg_status' => 1, 'status' => 1, 'katm_sir' => $katm_sir]);

            return response()->json(
                [
                    'message'=>'('.$code.') Online registratsiya muvaffaqiyatli bajarildi',
                    'type'=> $arrData['type'],
                    'data'=> $data_decode
                ]);

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
                    $clientComment = new UwClientComments();
                    $clientComment->uw_clients_id = $model_id;
                    $clientComment->claim_id = $claim_id;
                    $clientComment->work_user_id = Auth::user()->currentWork->id??0;
                    $clientComment->code = $result;
                    $clientComment->title = 'Oylik daromadi ma`lumotlari muvaffaqiyatli saqlandi. (XB)';
                    $clientComment->json_data = '';
                    $clientComment->process_type = 'SAL_X';
                    $clientComment->save();

                    $base64_decode = base64_decode($reportBase64);
                    $array = json_decode($base64_decode, true);
                    $array_client = $array['report']['client'];
                    $array_incomes_period = $array['report']['incomes_period'];
                    $array_sysinfo = $array['report']['sysinfo'];
                    $array_presence_reports = $array['report']['presence_reports'];
                    $array_notifications = $array['report']['notifications'];

                    $array_merge = array_merge([
                        'client' => $array_client,
                        'incomes_period' => $array_incomes_period,
                        'sysinfo' => $array_sysinfo,
                        'presence_reports' => $array_presence_reports,
                        'array_notifications' => $array_notifications
                    ]);
                    $base_json = json_encode($array_merge);
                    $base_file = base64_decode($base_json);
                    // base64 save
                    $baseFile = new UwPhyInpsBaseFile();
                    $baseFile->uw_clients_id = $model_id;
                    $baseFile->base_file = $base_file;
                    $baseFile->save();

                    if ($array['report']['incomes']) {

                        $array_income = $array['report']['incomes']['INCOME'];
                        if (array_filter($array_income, 'is_array')) {

                            $old_salary = UwInpsClients::where('uw_clients_id', $model_id);
                            $old_salary->delete();

                            foreach ($array_income as $key => $value) {
                                $inps = new UwInpsClients();

                                $year = substr($value['PERIOD'], 0,4);
                                $month = substr($value['PERIOD'], 5,6);

                                $inps->uw_clients_id = $model_id;
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

                            $inps = new UwInpsClients();

                            $year = substr($array_income['PERIOD'], 0,4);
                            $month = substr($array_income['PERIOD'], 5,6);

                            $inps->uw_clients_id = $model_id;
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

                        $inps = new UwInpsClients();
                        $inps->uw_clients_id = $model_id;
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

}
