<?php

namespace App\Http\Controllers;

use App\UwClientComments;
use App\UwClientDebtors;
use App\UwClientFiles;
use App\UwClients;
use App\UwInpsClients;
use App\UwKatmClients;
use App\UwLoanTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UwInquiryIndividualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return array
     */

    public function clientCreditResults($id)
    {
        //
        $model = UwClients::find($id);

        $clientTotalSum = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)->groupBy('claim_id')->sum('INCOME_SUMMA');
        $clientTotalSumMonthly = UwInpsClients::where('claim_id', $model->claim_id)->where('status', 1)->groupBy('PERIOD')->get()->count();
        $clientK = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();
        $scoringBall = json_decode($clientK['katm_score'], true);

        // Client Debtors Payment Calculate
        $debPayment = UwClientDebtors::where('uw_clients_id', $id)->get();
        $d_pay = 0;
        if ($debPayment){
            foreach ($debPayment as $key => $value){
                $d_pay+=$value->total_sum/$value->total_month * 0.87 * 0.5;
            }
        }

        $creditDebt = 0;
        $totalMonthPayment = 0;
        $creditCanBe = 0;
        $creditCanBeAnn = 0;
        $monthlyPay = 0;
        $monthlyPayAnn = 0;
        $pv = 0;
        if ($clientK){
            $creditDebt = $clientK->katm_summ;
            $scoringBall = $clientK->katm_sc_ball;
        }
        if ($clientTotalSum){
            $totalMonthPayment = ($clientTotalSum / $clientTotalSumMonthly * 0.5) - $creditDebt;
            $creditCanBe = ($totalMonthPayment + $d_pay) * $model->loanType->credit_duration /(+$model->loanType->credit_duration*($model->loanType->procent/100)/365*30+1);
            $monthlyPay = $model->summa/$model->loanType->credit_duration + $model->summa*$model->loanType->procent * 0.01/365 * 30;

            $creditCanBeAnn = ($totalMonthPayment + $d_pay)*(pow(1+($model->loanType->procent*0.01/12), $model->loanType->credit_duration)-1)/($model->loanType->procent*0.01/12*(pow(1+($model->loanType->procent*0.01/12), $model->loanType->credit_duration)));
            $monthlyPayAnn = -(($pv - $model->summa) * $model->loanType->procent*0.01/12)/ (1 - pow((1 + $model->loanType->procent*0.01/12), (-$model->loanType->credit_duration)));
        }

        // In CS max sum can be
        if (Auth::user()->uwUsers() == 'credit_insp' && $creditCanBe >= $model->summa){
            $creditCanBe = $model->summa;
        }

        return [
            'credit_debt' => $creditDebt,
            'total_month_salary' => $clientTotalSum,
            'total_monthly' => $clientTotalSumMonthly,
            'total_month_payment' => $totalMonthPayment,
            'credit_sum' => $model->summa,
            'credit_can_be' => $creditCanBe,
            'scoring_ball' => $scoringBall,
            'monthly_pay' => $monthlyPay,
            'credit_can_be_ann' => $creditCanBeAnn,
            'monthly_pay_ann' => $monthlyPayAnn,
        ];

    }

    public function onlineRegistration(Request $request)
    {
        //
        $id = $request->id;

        $modelClient = UwClients::find($id);

        $modelLoanType = UwLoanTypes::find($modelClient->loan_type_id);

        if ($modelClient->reg_status == 1) {

            return $this->creditReportK($id, $modelClient->claim_id, $modelClient->is_inps);

        }

        $url = 'http://10.22.50.3:8000/inquiry/individual';

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

        $postdata = json_encode($data);

        $ch = curl_init($url);
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
        curl_close($ch);

        $data_decode = json_decode($result, true);
        $code = $data_decode['result']['code'];
        $message = $data_decode['result']['message'];

        if ($code == '05000') {

            // update reg
            $modelClient = UwClients::find($id);

            $modelClient->update(['reg_status' => 1]);

            $clientComment = new UwClientComments();
            $clientComment->uw_clients_id = $id;
            $clientComment->user_id = Auth::id();
            $clientComment->claim_id = $modelClient->claim_id;
            $clientComment->title = '(code:'.$code.') Online Registration Success';
            $clientComment->comment_type = '1';
            $clientComment->save();

            return $this->creditReportK($id, $modelClient->claim_id, $modelClient->is_inps);

        } else {
            return response()->json(
                [
                    'status'=>'warning',
                    'message'=>'('.$code.') KATM ro`yhatga olishda xatolik mavjud!',
                    'data'=> $message,
                ]);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditReportK($id, $claim_id, $is_inps)
    {
        //
        $katm_client = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();

        if ($katm_client){
            return $this->creditReportI($id, $claim_id);
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
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);

        $data_decode = json_decode($result, true);

        //$code = $data_decode['code'];
        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($result == '05050'){

            $token = $data_decode['data']['token'];

            return $this->creditReportStatusK($token, $id, $claim_id, $is_inps);

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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditReportStatusK($token, $id, $claim_id, $is_inps)
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
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result1 = curl_exec($ch1);
        curl_close($ch1);

        $data_decode1 = json_decode($result1, true);

        $reportBase64 = $data_decode1['data']['reportBase64'];

        if (!$reportBase64){
            return $this->creditReportStatusK($token, $id, $claim_id, $is_inps);
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

            //$file_path = "//172.16.1.123/T$/OSPanel/domains/edo.turonbank.uz/public/katm_files/".$file_name;
            $score_img_file = fopen($file_path, "w+") or die("Unable to open file!");
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

            $md_array_ft = $models_data['html']['body']['div'][1]['table'][2]['tbody']['tr'];
            $ft_claim_id = $md_array_ft[2]['td']['span']['span'];
            $ft_katm_id = $md_array_ft[3]['td']['span']['span'];

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
                'ft_claim_id' => $ft_claim_id,
                'ft_katm_id' => $ft_katm_id,
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

            $katm = UwKatmClients::updateOrCreate(['uw_clients_id' => $id],
                [
                    'uw_clients_id' => $id,
                    'claim_id' => $claim_id,
                    'katm_summ' => $summ,
                    'katm_sc_ball' => $scoring_ball,
                    'status' => 1,
                    'katm_score' => $arr_scoring_json,
                    'katm_tb' => $arr_tb_json
                ]);

            if ($is_inps == 1 && $scoring_ball > 199){

                return $this->creditReportI($id, $claim_id);

            } else {

                return response()->json(
                    [
                        'status'=>'success',
                        'message'=>'KATM Mijoz kredit tarixi muvaffaqiyatli saqlandi',
                        'is_inps'=> 1,
                        'data'=> $katm,
                        'credit_results' => $this->clientCreditResults($id),
                    ]);
            }
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditReportI($id, $claim_id)
    {
        //
        $inps_client = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)->first();

        if ($inps_client) {

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'KATM va INPS natijasi muvaffaqiyatli saqlandi',
                    'credit_results' => $this->clientCreditResults($id)
                ]);
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
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);

        $data_decode = json_decode($result, true);

        $code = $data_decode['code'];
        $result = $data_decode['data']['result'];
        $resultMessage = $data_decode['data']['resultMessage'];

        if ($code == '200'){
            if ($result == '05050'){

                $tokenI = $data_decode['data']['token'];

                return $this->creditReportStatusI($tokenI, $id, $claim_id);

            } else {
                return response()->json(
                    [
                        'status'=>'warning',
                        'message'=>'('.$code.') Сведения о доходах по ИНПС не найдены',
                        'data'=> $result.''.$resultMessage,
                        'credit_results' => $this->clientCreditResults($id)
                    ]);
            }

        } else {
            $errorMessage = $data_decode['data']['errorMessage'];

            return response()->json(
                [
                    'status'=>'warning',
                    'message'=>'('.$code.') Сведения о доходах по ИНПС не найдены',
                    'data'=> $result.''.$errorMessage,
                    'credit_results' => $this->clientCreditResults($id)
                ]);

        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditReportStatusI($tokenI, $id, $claim_id)
    {
        //
        $urlI = 'http://10.22.50.3:8001/katm-api/v1/credit/report/status';

        $dataI = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".Auth::user()->branch_code."",
                "pToken" => "".$tokenI."",
                "pLegal" => 1,
                "pClaimId" => "".$claim_id."",
                "pReportId" => 25,
                "pReportFormat" => 1
            ),
        );

        $postdata1 = json_encode($dataI);

        $ch1 = curl_init($urlI);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postdata1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result1 = curl_exec($ch1);
        curl_close($ch1);

        $data_decode1 = json_decode($result1, true);
        //$result = $data_decode1['data']['result'];
        $reportBase64 = $data_decode1['data']['reportBase64'];
        if (!$reportBase64){

            return $this->creditReportStatusI($tokenI, $id, $claim_id);

        } else{

            $base64_decode = base64_decode($reportBase64);

            $models = json_decode($base64_decode, true);

            if ($models['report']['incomes']) {
                $old_inps = UwInpsClients::where('uw_clients_id', $id)->where('status', 1);
                $old_inps->delete();

                $models_array = $models['report']['incomes']['INCOME'];
                if (array_filter($models_array, 'is_array')) {
                    # code...
                    foreach ($models_array as $key => $value) {
                        # code...
                        $inps = new UwInpsClients();

                        $inps->uw_clients_id = $id;
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

                    $inps->uw_clients_id = $id;
                    $inps->claim_id = $claim_id;
                    $inps->ORG_INN = $models_array['ORG_INN'];
                    $inps->INCOME_SUMMA = $models_array['INCOME_SUMMA'];
                    $inps->NUM = $models_array['NUM'];
                    $inps->PERIOD = $models_array['PERIOD'];
                    $inps->ORGNAME = $models_array['ORGNAME'];
                    $inps->status = 1;
                    $inps->save();
                }

                return response()->json(
                    [
                        'status'=>'success',
                        'message'=>'KATM va INPS ma`lumotlari muvaffaqiyatli saqlandi',
                        'credit_results' => $this->clientCreditResults($id)
                    ]);

            } else {
                $old_inps = UwInpsClients::where('uw_clients_id', $id)->where('status', 1);

                $old_inps->delete();

                return response()->json(
                    [
                        'status'=>'warning',
                        'message'=>'('.$models.') Сведения о доходах по ИНПС не найдены',
                        'credit_results' => $this->clientCreditResults($id),
                    ]);
            }

        }

    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResultButtons($id)
    {
        //
        $button_k = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();

        $button_i = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)->get();

        return response()->json(
            [
                'status' => '200',
                'data_k' => $button_k,
                'data_i' => $button_i,
                'credit_results' => $this->clientCreditResults($id)
            ]);
    }

    public function getClientKatm($id)
    {
        $model = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();

        $modelClient = UwClients::find($id);

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
        $getFileScoringImg = file_get_contents("katm_files/".$modelClient->claim_id.".php");
        //$getFileScoringImg = Storage::disk('disk_edo_123')->get("/katm_files/".$modelClient->claim_id.".php");

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

    public function getClientInps($id)
    {
        $result = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)->get();

        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusSend($id, $sch_type)
    {
        //
        $model = UwClients::find($id);
        $modelFile = UwClientFiles::where('uw_client_id', $id)->first();

        $clientTotalSum = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)->groupBy('claim_id')->sum('INCOME_SUMMA');
        $clientTotalSumMonthly = UwInpsClients::where('claim_id', $model->claim_id)->where('status', 1)->groupBy('PERIOD')->get()->count();
        $clientK = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();
        $scoringBall = json_decode($clientK['katm_score'], true);

        // Client Debtors Payment Calculate
        $debPayment = UwClientDebtors::where('uw_clients_id', $id)->get();
        $d_pay = 0;
        if ($debPayment){
            foreach ($debPayment as $key => $value){
                $d_pay+=$value->total_sum/$value->total_month * 0.87 * 0.5;
            }
        }

        $creditDebt = 0;
        $creditCanBe = 0;
        if ($clientK){
            $creditDebt = $clientK->katm_summ;
            $scoringBall = $clientK->katm_sc_ball;
        }
        if ($clientTotalSum){
            $totalMonthPayment = ($clientTotalSum / $clientTotalSumMonthly * 0.5) - $creditDebt;
            if ($sch_type == 1){
                $creditCanBe = ($totalMonthPayment+$d_pay) * $model->loanType->credit_duration /(+$model->loanType->credit_duration*($model->loanType->procent/100)/365*30+1);
            } else {
                $creditCanBe = ($totalMonthPayment+$d_pay) * (pow(1+($model->loanType->procent*0.01/12), $model->loanType->credit_duration)-1)/
                    ($model->loanType->procent*0.01/12*(pow(1+($model->loanType->procent*0.01/12), $model->loanType->credit_duration)));
            }
        }

        if (!$clientK){
            $status = 0;
            $modal_style = 'warning';
            $message = 'KATMga so`rov yuboring!!!';
        }
        elseif ($scoringBall < 200){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Skoring bali (200 ball) dan yuqori emas!!!';
        }
        elseif ($model->is_inps > 1 && $scoringBall < 200){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Skoring bali (200 ball) dan yuqori emas!!!';
        }
        elseif ($model->is_inps == 1 && $model->summa >= $creditCanBe){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Mijoz to`lov qobiliyati yetarli emas!!!';
        }
        /*elseif ($model->is_inps == 1 && $clientTotalSumMonthly < 3){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Mijozning Oylik ish xaqqi davri yetarli emas (3 oydan kam)!!!';
        }*/
        elseif ($model->is_inps == 1 && $scoringBall < 200 && $model->summa >= $creditCanBe){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Mijoz to`lov qobiliyati yetarli emas!!!';
        }
        elseif ($model->is_inps > 1 && !$modelFile){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Ilova hujjatlarini yuklang!!!';
        }
        else {
            $status = 1;
            $modal_style = 'success';
            $message = 'Arizani yuborishni tasdiqlang';
        }

        return response()->json([
            'status'   => $status,
            'modal_style'   => $modal_style,
            'message' => $message,
        ]);

    }

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
