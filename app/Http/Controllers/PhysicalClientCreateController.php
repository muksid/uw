<?php

namespace App\Http\Controllers;

use App\MWorkUsers;
use App\MyidDistricts;
use App\MyidIibDistricts;
use App\MyidRegions;
use App\PhyMyidClient;
use App\PhyMyidToken;
use App\UwClients;
use App\UwLoanTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PhysicalClientCreateController extends Controller
{
    //
    private $GRANT_TYPE = 'password';
    private $USERNAME   = 'turon_prod_8lkvH58M';
    private $PASSWORD   = 'xJ8oTq2fiIj8T8Opw2BAFNUx6bdjKTxU9PQypcYLkT57ztcChAej2Ncg5AaVD3c6';
    private $CLIENT_ID  = 'turonbank_inplace-lUUmzndTpHCk5R6e7EcgJZ7QYpYA33y2olqc4ZMF';

    public $URL_ACCESS_TOKEN  = 'https://myid.uz/api/v1/oauth2/access-token';

    public $URL_REQUEST_TASK  = 'https://myid.uz/api/v1/authentication/simple-inplace-authentication-request-task';

    public $URL_REQUEST_STATUS  = 'https://myid.uz/api/v1/authentication/simple-inplace-authentication-request-status';

    public function index()
    {

        return view('phy.ins.new.index');

    }

    public function createPersonal(Request $request)
    {
        //print_r($request->all());

        $passport = $request->pass_data;

        $checkModel = UwClients::where(function ($query) use ($passport) {
                $query->where(DB::raw("CONCAT(`document_serial`,`document_number`)"), '=',$passport);
            })
            ->where(DB::raw("(STR_TO_DATE(birth_date,'%Y-%m-%d'))"), "=", $request->birth_date)
            ->where(function ($status){
                $status->where('status', '!=', -1);
            })->first();

        if ($checkModel) {

            return response()->json(['code' => 'model', 'message' => $checkModel->branch_code.' INSPEKTOR: '.$checkModel->currentWork->personal->l_name??'-']);
        }

        $checkClient = PhyMyidClient::where('pass_data', '=', $passport)
            ->where(DB::raw("(STR_TO_DATE(birth_date,'%Y-%m-%d'))"), "=", $request->birth_date)
            ->first();

        if ($checkClient) {

            return response()->json(['code' => 'myid', 'id' => $checkClient->id]);

        }

        $post = array(
            'grant_type'  => $this->GRANT_TYPE,
            'username'    => $this->USERNAME,
            'password'    => $this->PASSWORD,
            'client_id'   => $this->CLIENT_ID
        );

        $token = PhyMyidToken::orderBy('created_at', 'desc')->first();

        if ($token) {
            $startTime = Carbon::parse($token->created_at);
            $finishTime = Carbon::now();

            $totalDuration = $finishTime->diffInSeconds($startTime);

            if ($totalDuration <= 3600) {

                $arr = array(
                    'token' => $token->access_token,
                    'client_id' => $this->CLIENT_ID,
                    'job_id' => $request->job_id,
                    'pass_data' => $request->pass_data,
                    'birth_date' => $request->birth_date,
                    'image' => $request->image,
                    'totalDuration' => $totalDuration,
                );

                /*if ($request->job_id) {

                    return $this->getClientData($arr);

                }*/

                return $this->getJobId($arr);

            } else {
                $ch = curl_init($this->URL_ACCESS_TOKEN);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
                curl_setopt($ch, CURLOPT_PROXY, '192.168.2.111:3128');
                curl_setopt($ch, CURLOPT_HTTPHEADER, $post);
                $result = curl_exec($ch);
                curl_close($ch);

                $result_req = json_decode($result, true);

                $access_token = $result_req['access_token'];

                $refresh_token = $result_req['refresh_token'];

                $old_token = PhyMyidToken::all();
                if ($old_token) {
                    PhyMyidToken::orderBy('created_at')->delete();
                }

                $myid_token = new PhyMyidToken();
                $myid_token->access_token = $access_token;
                $myid_token->refresh_token = $refresh_token;
                $myid_token->save();

                $arr = array(
                    'token' => $myid_token->access_token,
                    'client_id' => $this->CLIENT_ID,
                    'pass_data' => $request->pass_data,
                    'birth_date' => $request->birth_date,
                    'image' => $request->image,
                );

                return $this->getJobId($arr);

            }

        } else {

            $ch = curl_init($this->URL_ACCESS_TOKEN);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
            curl_setopt($ch, CURLOPT_PROXY, '192.168.2.111:3128');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $post);
            $result = curl_exec($ch);
            curl_close($ch);
            //return response()->json($result);
            $result_req = json_decode($result, true);

            $access_token = $result_req['access_token'];

            $refresh_token = $result_req['refresh_token'];

            $old_token = PhyMyidToken::orderBy('id')->get();
            if ($old_token) {
                PhyMyidToken::orderBy('id')->delete();
            }

            $myid_token = new PhyMyidToken();
            $myid_token->access_token = $access_token;
            $myid_token->refresh_token = $refresh_token;
            $myid_token->save();

            $arr = array(
                'token' => $myid_token->access_token,
                'client_id' => $this->CLIENT_ID,
                'pass_data' => $request->pass_data,
                'birth_date' => $request->birth_date,
                'image' => $request->image,
            );

            return $this->getJobId($arr);

        }

    }

    public function getJobId($arr)
    {

        $data = array(
            'pass_data' => $arr['pass_data'],
            'birth_date' => $arr['birth_date'],
            'photo_from_camera' => array(
                'front' => $arr['image'],
                'left' => '',
                'right' => '',
                'smile' => '',
                'focus' => '',
            ),
            'agreed_on_terms' => true,
            'client_id' => $arr['client_id'],
            'device' => ''
        );

        $data_json = json_encode($data);

        $ch = curl_init($this->URL_REQUEST_TASK);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '192.168.2.111:3128');


        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $arr['token']
        ));

        $result = curl_exec($ch);

        curl_close($ch);

        $result_req = json_decode($result, true);

        $job_id = $result_req['job_id'];

        $arr = array(
            'token' => $arr['token'],
            'img' => $data['photo_from_camera']['front'],
            'job_id' => $job_id
        );

        //return response()->json(['result' => $result, 'arr' => $arr]);

        return $this->getClientData($arr);


    }

    public function getClientData($arr)
    {
        //
        $job_id = $arr['job_id'];

        $url = $this->URL_REQUEST_STATUS.'?job_id='.$job_id;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, '192.168.2.111:3128');

        $header = array('Content-Type: application/json',
            'Authorization: Bearer ' . $arr['token']);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $returnData = curl_exec ($ch);

        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status_code == '202') {
            return $this->getClientData($arr);
        }

        $data_arr = json_decode($returnData, true);

        curl_close($ch);

        if ($status_code == '200') {

            if ($data_arr['result_code'] == 1) {

                $today = Carbon::today();
                $year = $today->year;
                $month = $today->month;
                $day = $today->day;
                $img_client = $arr['img'];

                if (preg_match('/^data:image\/(\w+);base64,/', $img_client)) {
                    $data = substr($img_client, strpos($img_client, ',') + 1);

                    $data = base64_decode($data);

                    $hash_filename = md5(Carbon::now() . Auth::id());

                    $path_img = 'uw/phy/myid/' . $year . '/' . $month . '/' . $day . '/' . $hash_filename . '.jpeg';

                    Storage::disk('ftp_nas')->put($path_img, $data);
                }

                $dataProfile = $data_arr['profile'];
                $common_data = $dataProfile['common_data'];
                $doc_data = $dataProfile['doc_data'];
                $contacts = $dataProfile['contacts'];
                $address = $dataProfile['address'];

                $phyMyidClient = new PhyMyidClient();
                // common_data
                $phyMyidClient->first_name = $common_data['first_name'];
                $phyMyidClient->middle_name = $common_data['middle_name'];
                $phyMyidClient->last_name = $common_data['last_name'];
                $phyMyidClient->pinfl = $common_data['pinfl'];
                $phyMyidClient->inn = $common_data['inn'];
                $phyMyidClient->gender = $common_data['gender'];
                $phyMyidClient->birth_place = $common_data['birth_place'];
                $phyMyidClient->birth_country = $common_data['birth_country'];
                $phyMyidClient->birth_date = date('Y-m-d', strtotime($common_data['birth_date']));
                $phyMyidClient->nationality = $common_data['nationality'];
                $phyMyidClient->citizenship = $common_data['citizenship'];
                // doc_data
                $phyMyidClient->pass_data = $doc_data['pass_data'];
                $phyMyidClient->issued_by = $doc_data['issued_by'];
                $phyMyidClient->issued_by_id = $doc_data['issued_by_id'];
                $phyMyidClient->issued_date = date('Y-m-d', strtotime($doc_data['issued_date']));
                $phyMyidClient->expiry_date = date('Y-m-d', strtotime($doc_data['expiry_date']));
                // contacts
                $phyMyidClient->phone = $contacts['phone'];
                $phyMyidClient->email = $contacts['email'];
                // address
                $phyMyidClient->permanent_address = $address['permanent_address'];
                $phyMyidClient->permanent_registration = json_encode($address['permanent_registration']);

                if ($address['temporary_address']) {
                    $phyMyidClient->temporary_address = $address['temporary_address'];
                    $phyMyidClient->temporary_registration = json_encode($address['temporary_registration']);
                }

                $phyMyidClient->branch_code = Auth::user()->currentWork->branch_code??'09011';
                $phyMyidClient->work_user_id = Auth::user()->currentWork->id??0;
                $phyMyidClient->img_path = $path_img;
                $phyMyidClient->isActive = 'A';
                $phyMyidClient->save();

                return response()->json(['code' => 'myid', 'id' => $phyMyidClient->id]);

            } else {

                $result_code = $data_arr['result_code'];

                $data = $data_arr['result_note'];

                return response()->json([
                    'arr' => $arr,
                    'job_id' => $job_id,
                    'data' => $data,
                    'code' => $status_code,
                    'result_code' => $result_code
                ]);

            }
        }

        return response()->json($data_arr);

    }

    public function clientForm($id)
    {

        $model = PhyMyidClient::findOrFail($id);

        $address = json_decode($model->permanent_registration, true);

        $loans = UwLoanTypes::select('credit_type', 'title')->where('short_code', '!=', 'J')->groupBy('credit_type')->get();

        return view('phy.ins.new.form', compact('model', 'loans', 'address'));
    }

    public function createApp(Request $request)
    {
        $currentWorkUser = MWorkUsers::where('user_id', Auth::id())->where('isActive', 'A')->first();
        if (!$currentWorkUser){
            return back()->with(
                [
                    'status' => 'warning',
                    'message' => 'Inspektor passive holatda!!! (ip:247)'
                ]);
        }

        $credit_type = $request->credit_type;
        $document_type = $request->document_type;
        $req_phone = $request->phone;
        $live_number = $request->live_number;
        $is_inps = $request->is_inps;
        $job_address = $request->job_address;
        $req_summa = $request->summa;
        $myid_id = $request->myid_id;

        $phone = preg_replace('/[^A-Za-z0-9]/', '', $req_phone);

        $str_summa = str_replace(',', '.', $req_summa);

        $summa = str_replace(' ', '', $str_summa);

        $branchCode = $currentWorkUser->branch_code;
        //$localCode = Department::find($currentWorkUser->depart_id);

        $myid_client = PhyMyidClient::findOrFail($myid_id);
        $pass_serial = substr($myid_client->pass_data, 0, 2);
        $pass_number = substr($myid_client->pass_data, 2, 7);

        $json_decode_region = json_decode($myid_client->permanent_registration, true);
        $regions = MyidRegions::where('code', '=', $json_decode_region['region_id'])->first();
        $districts = MyidDistricts::where('code', '=', $json_decode_region['district_id'])->first();

        $iib_districts = MyidIibDistricts::where('code', '=', $myid_client->issued_by_id)->first();
        $document_region = $regions->un_code??0;
        $document_district = $districts->un_code??0;
        if ($iib_districts) {
            $document_region = $iib_districts->region_code;
            $document_district = $iib_districts->district_code;
        }

        $loan = UwLoanTypes::find($credit_type)->first();

        $reg_region = $regions->un_code??0;
        $reg_district = $districts->un_code??0;

        $lastModelId = UwClients::where('branch_code', '=', $branchCode)->latest()->first();
        $claim_number = $lastModelId->claim_number + 1;
        $claim_id = '1'.$branchCode.$claim_number;

        $model = new UwClients();
        $model->loan_type_id = $credit_type;
        $model->work_user_id = $currentWorkUser->id;
        $model->branch_code = $branchCode;;
        $model->local_code = $currentWorkUser->local_code;
        $model->claim_id = $claim_id;
        $model->claim_date = today();
        $model->inn = $myid_client->inn;
        $model->claim_number = $claim_number;
        $model->agreement_number = $claim_number;
        $model->agreement_date = today();
        $model->resident = 1;
        $model->document_type = $document_type;
        $model->document_serial = $pass_serial;
        $model->document_number = $pass_number;
        $model->document_date = $myid_client->issued_date;
        $model->gender = $myid_client->gender;
        $model->client_type = "08";
        $model->birth_date = $myid_client->birth_date;
        $model->document_region = $document_region; //?????
        $model->document_district = $document_district; //?????
        $model->nibbd = "";
        $model->family_name = $myid_client->last_name;
        $model->name = $myid_client->first_name;
        $model->patronymic = $myid_client->middle_name;
        $model->registration_region = $reg_region;
        $model->registration_district = $reg_district;
        $model->registration_address = $myid_client->permanent_address;
        $model->phone = $phone;
        $model->pin = $myid_client->pinfl;
        $model->credit_type = $loan->credit_type;
        $model->summa = $summa;
        $model->live_address = $myid_client->permanent_address;
        $model->live_number = $live_number;
        $model->job_address = $job_address;
        $model->status = 1;
        $model->reg_status = 0;
        $model->is_inps = $is_inps;
        $model->save();

        return redirect()->route('phy.create.step.result', ['id' => $model->id])->with(
            [
                'status' => 'info',
                'message' => 'Mijoz muvaffaqiyatli tizimga qo`shildi',
            ]);
    }

    public function displayImage($id)
    {
        $model = PhyMyidClient::find($id);

        $myFile = Storage::disk('ftp_nas')->get($model->img_path);

        $response = Response::make($myFile, 200);

        $response->header("Content-Type", 'image/jpeg');

        return $response;

    }

}
