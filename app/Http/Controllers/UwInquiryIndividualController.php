<?php

namespace App\Http\Controllers;

use App\MWorkUsers;
use App\UwClientComments;
use App\UwClientDebtors;
use App\UwClientFiles;
use App\UwClients;
use App\UwInpsClients;
use App\UwInpsDebClients;
use App\UwKatmClients;
use App\UwKatmDebClients;
use App\UwLoanTypes;
use App\UwPhyInpsBaseFile;
use App\UwPhyKatmBaseFile;
use App\UwPhyKatmFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $isVersion = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)->first();

        if ($isVersion && $isVersion->isVersion == 2) {

            $clientTotalSumMonthly  = DB::select(
                DB::raw('SELECT concat(a.PERIOD,"-",a.NUM) as MON_PERIOD FROM uw_inps_clients a 
            where a.status = 1 and a.uw_clients_id =  '.$id.' and a.INCOME_SUMMA > 0 group by MON_PERIOD'));

            $clientTotalSumMonthly = count($clientTotalSumMonthly);

            $clientTotalSum = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)
                ->groupBy('uw_clients_id')
                ->sum(DB::raw('INCOME_SUMMA-salary_tax_sum'));

        } else {

            $clientTotalSumMonthly  = DB::select(
                DB::raw('SELECT concat(a.PERIOD,"-",a.NUM) as MON_PERIOD FROM uw_inps_clients a 
            where a.status = 1 and a.uw_clients_id =  '.$id.' and a.INCOME_SUMMA > 0 group by MON_PERIOD'));

            $clientTotalSumMonthly = count($clientTotalSumMonthly);

            $clientTotalSum = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)
                ->groupBy('uw_clients_id')->sum('INCOME_SUMMA');
        }


        $clientK = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();

        //$scoringBall = json_decode($clientK['katm_score'], true);

        // Client Debtors Payment Calculate
        $debPayment = UwClientDebtors::where('uw_clients_id', $id)->get();
        $d_pay = 0;
        if ($debPayment){

            foreach ($debPayment as $key => $value){
                $d_pay+=$value->total_sum/$value->total_month * 0.87 * $model->loanType->dept_procent/100;
            }
        }

        $creditDebt = 0;
        $totalMonthPayment = 0;
        $creditCanBe = 0;
        $creditCanBeAnn = 0;
        $monthlyPay = 0;
        $monthlyPayAnn = 0;
        $scoringBall = 0;
        $pv = 0;
        if ($clientK){
            $creditDebt = $clientK->summa;
            $scoringBall = $clientK->scoring_ball;
        }
        if ($clientTotalSum){
            $totalMonthPayment = ($clientTotalSum / $clientTotalSumMonthly * $model->loanType->dept_procent/100) - $creditDebt;
            $creditCanBe = ($totalMonthPayment + $d_pay) * $model->loanType->credit_duration /(+$model->loanType->credit_duration*($model->loanType->procent/100)/365*30+1);
            $monthlyPay = $model->summa/$model->loanType->credit_duration + $model->summa*$model->loanType->procent * 0.01/365 * 30;

            $creditCanBeAnn = ($totalMonthPayment + $d_pay)*(pow(1+($model->loanType->procent*0.01/12), $model->loanType->credit_duration)-1)/($model->loanType->procent*0.01/12*(pow(1+($model->loanType->procent*0.01/12), $model->loanType->credit_duration)));
            $monthlyPayAnn = -(($pv - $model->summa) * $model->loanType->procent*0.01/12)/ (1 - pow((1 + $model->loanType->procent*0.01/12), (-$model->loanType->credit_duration)));
        }
        // In CS max sum can be
        if ($creditCanBe >= $model->summa){
            $creditCanBe = $model->summa;
        }

        // In CS max sum can be
        if ($creditCanBeAnn >= $model->summa){
            $creditCanBeAnn = $model->summa;
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

    public function onlineRegistration(Request $request)
    {
        //
        $currentWorkUser = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();
        if (!$currentWorkUser){
            return response()->json(
                [
                    'status'=>'warning',
                    'message'=>'Inspektor passive holatda!!! (ip:247)'
                ]);
        }

        $id = $request->id;

        $modelClient = UwClients::find($id);

        $modelLoanType = UwLoanTypes::find($modelClient->loan_type_id);

        if ($modelClient->reg_status == 1) {

            return $this->creditReportK($id, $modelClient->claim_id, $modelClient->branch_code, $modelClient->is_inps);

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
        $katm_sir = $data_decode['response']['katm_sir'];

        if ($code == '05000') {

            $clientComment = new UwClientComments();
            $clientComment->uw_clients_id = $id;
            $clientComment->claim_id = $modelClient->claim_id;
            $clientComment->code = $code;
            $clientComment->work_user_id = $currentWorkUser->id;
            $clientComment->katm_sir = $katm_sir;
            $clientComment->json_data = $result;
            $clientComment->title = $message.' - Online registratsiya muvaffaqiyatli bajarildi';
            $clientComment->process_type = 'R';
            $clientComment->save();

            // update reg
            $modelClient->update(['reg_status' => 1, 'katm_sir' => $katm_sir]);

            return $this->creditReportK($id, $modelClient->claim_id, $modelClient->branch_code, $modelClient->is_inps);

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
    public function creditReportK($id, $claim_id, $branch_code, $is_inps)
    {
        //
        $isKATM = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();

        if ($isKATM){

            return $this->getClientSalary($id, $claim_id, $branch_code, $is_inps);
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

            return $this->creditReportStatusK($id, $claim_id, $branch_code, $is_inps, $token);

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
    public function creditReportStatusK($id, $claim_id, $branch_code, $is_inps, $token)
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

                    // save katm base file
                    $katmBaseFile = new UwPhyKatmBaseFile();
                    $katmBaseFile->uw_clients_id = $id;
                    $katmBaseFile->uw_katm_id = $katm->id;
                    $katmBaseFile->base_file = $txt_base64;
                    $katmBaseFile->save();

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

                        return $this->getClientSalary($id, $claim_id, $branch_code, $is_inps);

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
            return $this->creditReportStatusK($id, $claim_id, $branch_code, $is_inps, $token);
        }


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientSalary($id, $claim_id, $branch_code, $is_inps)
    {
        //
        return $this->getClientSalaryInps($id, $claim_id, $branch_code, $is_inps);
        $inps_client = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)->first();

        if ($inps_client) {

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'KATM va Oylik daromadi natijasi muvaffaqiyatli saqlandi',
                    'credit_results' => $this->clientCreditResults($id)
                ]);
        }

        $base_url = 'http://10.22.50.3:8001/katm-api/v1/credit/report';

        $postParam = array(
            "security" => array(
                "pLogin" => "turonbank",
                "pPassword" => "!trB&GkL@200130"
            ),
            "data" => array(
                "pHead" => "011",
                "pCode" => "".$branch_code."",
                "pLegal" => 1,
                "pClaimId" => "".$claim_id."",
                "pReportId" => "048",
                "pReportFormat" => 1
            ),
        );

        $postParamEncode = json_encode($postParam);

        $ch1 = curl_init($base_url);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postParamEncode);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch1, CURLOPT_PROXY, '10.22.50.3:8001');
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result_post = curl_exec($ch1);
        curl_close($ch1);

        $resultDecode = json_decode($result_post, true);

        $code = $resultDecode['code'];
        //print_r($resultCode); die;

        if ($code == '200') {

            $result_code = $resultDecode['data']['result'];

            if ($result_code == '05000') {
                $message = $resultDecode['data']['resultMessage'];
                $resultBase64 = $resultDecode['data']['reportBase64'];
                $base64_decode = base64_decode($resultBase64);
                $models_decode = json_decode($base64_decode, true);
                $models_status = $models_decode['report']['success'];

                $clientComment = new UwClientComments();
                $clientComment->uw_clients_id = $id;
                $clientComment->claim_id = $claim_id;
                $clientComment->work_user_id = Auth::user()->currentWork->id??0;
                $clientComment->code = $result_code;
                $clientComment->title = 'Oylik ish haqi ma`lumotlari muvaffaqiyatli saqlandi';
                $clientComment->json_data = '';
                $clientComment->process_type = 'SAL';
                $clientComment->save();

                if ($models_status == 1) {
                    // code...
                    $models = $models_decode['report']['data'];

                    $old_inps = UwInpsClients::where('uw_clients_id', $id)->where('status', 1);

                    $old_inps->delete();

                    if (array_filter($models, 'is_array')) {

                        $today = Carbon::today();
                        $year = $today->year - 1;
                        $month = $today->month;

                        foreach ($models as $key => $value) {
                            # code...
                            $inps = new UwInpsClients();
                                if ($value['year'] >= $year && ($value['period'] >= $month || $value['year'] > $year)){

                                    $inps->uw_clients_id = $id;
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
                                'status' => 'success',
                                'message' => '('.$message.') KATM KIAS Scoring va Oylik daromadi ma`lumotlari muvaffaqiyatli saqlandi',
                                'credit_results' => $this->clientCreditResults($id),
                                'models' => $models
                            ]);

                    }
                }


            } else {
                $message = $resultDecode['data']['resultMessage'];

                return response()->json(
                    [
                        'status' => 'success',
                        'message' => '('.$message.')',
                        'credit_results' => $this->clientCreditResults($id)
                    ]);

            }
        }
        else {

            return $this->getClientSalaryInps($id, $claim_id, $branch_code, $is_inps);

            /*$message = $resultDecode['errorMessage'];

            $code = $resultDecode['code'];

            return response()->json(
                [
                    'status'=>'danger',
                    'message'=> $message,
                    'code' => $code
                ]);*/
        }

    }

    public function getClientSalaryInps($id, $claim_id, $branch_code, $is_inps)
    {

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

        //print_r($data_decode); die;

        if ($code == '200'){
            if ($result == '05050'){
                $token = $data_decode['data']['token'];
                return $this->getClientSalaryInpsStatus($id, $claim_id, $branch_code, $is_inps, $token);

            } else {
                return response()->json(
                    [
                        'status'=>'danger',
                        'message'=> $resultMessage,
                        'code' => $code
                    ]);
            }

        } else {
            $errorMessage = $data_decode['errorMessage'];

            return response()->json(
                [
                    'status'=>'danger',
                    'message'=> 'code: '.$code.') '.$errorMessage,
                    'data' => $code
                ]);
        }

    }

    public function getClientSalaryInpsStatus($id, $claim_id, $branch_code, $is_inps, $token){

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
                "pReportId" => "25",
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
        $json = curl_exec($ch1);
        curl_close($ch1);

        $data_decode = json_decode($json, true);
        $result = $data_decode['data']['result'];
        $reportBase64 = $data_decode['data']['reportBase64'];
        $code = $data_decode['code'];

        //print_r($data_decode); die;

        if ($code == '200') {

            if ($result == '05000') {

                $clientComment = new UwClientComments();
                $clientComment->uw_clients_id = $id;
                $clientComment->claim_id = $claim_id;
                $clientComment->work_user_id = Auth::user()->currentWork->id??'0';
                $clientComment->code = $result;
                $clientComment->title = 'Oylik ish haqi ma`lumotlari muvaffaqiyatli saqlandi. (XB)';
                $clientComment->json_data = '';
                $clientComment->process_type = 'SAL_X';
                $clientComment->save();

                if ($reportBase64) {
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
                    $baseFile->uw_clients_id = $id;
                    $baseFile->base_file = $base_file;
                    $baseFile->save();

                    if ($array['report']['incomes']) {

                        $old_salary = UwInpsClients::where('uw_clients_id', $id);
                        $old_salary->delete();

                        $array_income = $array['report']['incomes']['INCOME'];
                        if (array_filter($array_income, 'is_array')) {

                            foreach ($array_income as $key => $value) {
                                $inps = new UwInpsClients();

                                $year = substr($value['PERIOD'], 0,4);
                                $month = substr($value['PERIOD'], 5,6);

                                $inps->uw_clients_id = $id;
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

                            $inps->uw_clients_id = $id;
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
                        $inps->uw_clients_id = $id;
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
                            'status' => 'success',
                            'message' => '('.$result.') KATM KIAS Scoring va Oylik daromadi ma`lumotlari 
                                    muvaffaqiyatli saqlandi',
                            'credit_results' => $this->clientCreditResults($id)
                        ]);

                } else {
                    return $this->getClientSalaryInpsStatus($id, $claim_id, $branch_code, $is_inps, $token);
                }

            } elseif ($result == '05050' and (!$reportBase64)) {
                return $this->getClientSalaryInpsStatus($id, $claim_id, $branch_code, $is_inps, $token);
            }

        } else {

            $message = $data_decode['errorMessage'];

            return response()->json(
                [
                    'status' => 'error',
                    'message' => '('.$code.')'.$message,
                    'credit_results' => $this->clientCreditResults($id)
                ]);
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

    public function getScoring(Request $request)
    {
        $id = $request->id;

        $scoring_k = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();

        $scoringBase64 = UwPhyKatmBaseFile::where('uw_katm_id', $scoring_k->id)->first();

        $katmBase64 = UwPhyKatmFile::where('uw_clients_id', $id)->where('file_type', 'B64')->orderBy('id', 'desc')->first();

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

        $clientModel = UwClients::find($id);

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

    public function getSalary(Request $request)
    {
        $id = $request->id;
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

        $modelDebtors = UwClientDebtors::where('uw_clients_id',$id)->get();

        $selected = $modelDebtors->implode('id', ',');
        $explode = explode(',', $selected);
        
        $KATM = UwKatmClients::where('uw_clients_id', $id)->where('status', 1)->first();

        $KATM_DEB = UwKatmDebClients::whereIn('uw_deb_id', $explode)->where('status', 1)->get();

        $modelFile = UwClientFiles::where('uw_client_id', $id)->first();

        $clientTotalSumMonthly  = DB::select(
            DB::raw('SELECT concat(a.PERIOD,"-",a.NUM) as SUM FROM uw_inps_clients a 
            where a.status = 1 and a.uw_clients_id =  '.$id.' and a.INCOME_SUMMA > 0 group by sum'));

        $clientTotalSumMonthly = count($clientTotalSumMonthly);

        $clientTotalSum = UwInpsClients::where('uw_clients_id', $id)->where('status', 1)
            ->groupBy('uw_clients_id')
            ->sum(DB::raw('INCOME_SUMMA-salary_tax_sum'));

        // Client Debtors Payment Calculate
        $debPayment = UwClientDebtors::where('uw_clients_id', $id)->get();
        $d_pay = 0;

        if (empty($debPayment)){
            foreach ($debPayment as $key => $value){

                if ($value->has_salary == 'N') {
                    $d_pay+=$value->total_sum/$value->total_month * 0.87 * $model->loanType->dept_procent/100;
                }else {
                    $clientTotalSumDeb = UwInpsDebClients::where('uw_deb_id', $value->id)->where('status', 1)
                        ->groupBy('uw_deb_id')
                        ->sum(DB::raw('INCOME_SUMMA-salary_tax_sum'));

                    $clientTotalSumMonthlyDeb  = DB::select(
                        DB::raw('SELECT concat(a.PERIOD,"-",a.NUM) as SUM FROM uw_inps_deb_clients a 
                        where a.status = 1 and a.uw_deb_id =  '.$value->id.' and a.INCOME_SUMMA > 0 group by sum'));

                    $clientTotalSumMonthlyDeb = count($clientTotalSumMonthlyDeb);
 
                    if($clientTotalSumMonthlyDeb > 0){

                        $d_pay+=$clientTotalSumDeb/$clientTotalSumMonthlyDeb * 0.87 * $model->loanType->dept_procent/100;
                    }else{
                        $status = 0;
                        $modal_style = 'warning';
                        $message = 'Kafildor shaxning ish xaqi ro`yxatdan o`tkazilmagan!!!';

                        return response()->json([
                            'status'   => $status,
                            'modal_style'   => $modal_style,
                            'message' => $message
                        ]);
                    }
                }

            }
        }

        $creditDebt = 0;
        $creditCanBe = 0;
        $arr_row_7 = 0;
        $arr_row_8 = 0;
        $arr_row_9 = 0;

        if ($KATM){
            $creditDebt = $KATM->summa;
            $scoringBall = $KATM->scoring_ball;
            $katm_base_file = UwPhyKatmBaseFile::where('uw_katm_id', $KATM->id)->where('base_file', '!=', null)->first();
            /*if ($katm_base_file) {
                $base_file = base64_decode($katm_base_file->base_file);
                $base_arr = json_decode($base_file, true);
                $newstring = substr($base_arr, -5);
                //print_r($base_file."\"]}}]"); die;



                $arr_row_7 = $base_arr['25']['td']['4']['span'];
                $arr_row_8 = $base_arr['26']['td']['4']['span'];
                $arr_row_9 = $base_arr['27']['td']['4']['span'];
            }*/


        }
        
        if (empty($KATM_DEB)){
            foreach ($KATM_DEB as $key => $value) {
                $creditDebtDeb += $value->summa;
                $scoringBallDeb = $value->scoring_ball;

                $katm_base_fileDeb = UwPhyKatmBaseDebFile::where('uw_katm_id', $value->id)->where('base_file', '!=', null)->first();

                if (!$value){
                    $status = 0;
                    $modal_style = 'warning';
                    $message = 'KATMga so`rov yuboring (Kafil, qo`shimcha qarzdor, ...)!!!';

                    return response()->json([
                        'status'   => $status,
                        'modal_style'   => $modal_style,
                        'message' => $message
                    ]);
                }elseif ($scoringBallDeb < 200) {
                    $status = 0;
                    $modal_style = 'warning';
                    $message = 'Skoring bali (200 ball) dan yuqori emas!!!';

                    return response()->json([
                        'status'   => $status,
                        'modal_style'   => $modal_style,
                        'message' => $message
                    ]);
                }
            }

        }
        if(isset($creditDebtDeb)) $creditDebt += $creditDebtDeb;

        if ($clientTotalSum){
            $totalMonthPayment = ($clientTotalSum / $clientTotalSumMonthly * $model->loanType->dept_procent/100) - $creditDebt;
            if ($sch_type == 1){
                $creditCanBe = ($totalMonthPayment+$d_pay) * $model->loanType->credit_duration /(+$model->loanType->credit_duration*($model->loanType->procent/100)/365*30+1);
            } else {
                $creditCanBe = ($totalMonthPayment+$d_pay) * (pow(1+($model->loanType->procent*0.01/12), $model->loanType->credit_duration)-1)/
                    ($model->loanType->procent*0.01/12*(pow(1+($model->loanType->procent*0.01/12), $model->loanType->credit_duration)));
            }
        }

        if (!$KATM){
            $status = 0;
            $modal_style = 'warning';
            $message = 'KATMga so`rov yuboring!!!';
        }
        elseif ($scoringBall < 200){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Skoring bali (200 ball) dan yuqori emas!!!';
        }
        elseif ($arr_row_7 > 0){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Просрочки от 30 до 60 дней!!!';
        }
        elseif ($arr_row_8 > 0){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Просрочки от 60 до 90 дней!!!';
        }
        elseif ($arr_row_9 > 0){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Просрочки от 90 дней и более!!!';
        }
        elseif ($model->is_inps == 1 && $model->summa >= intval($creditCanBe)){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Mijoz to`lov qobiliyati yetarli emas!!!';
        }
        elseif ($model->is_inps > 1 && !$modelFile){
            $status = 0;
            $modal_style = 'warning';
            $message = 'Ilova hujjatlarini yuklang!!!';
        }
        elseif ($model->is_inps == 2 && $clientTotalSumMonthly < 2 ){

            $status = 0;
            $modal_style = 'warning';
            $message = 'Mijozning Oylik daromadi davri yetarli emas (3 oydan kam)!!!';
        }

        else {
            $status = 1;
            $modal_style = 'success';
            $message = 'Arizani yuborishni tasdiqlang';
        }

        // "Ta`lim" credit type = 59   $model->loanType->credit_type != 59
        
        return response()->json([
            'status'   => $status,
            'modal_style'   => $modal_style,
            'message' => $message
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
