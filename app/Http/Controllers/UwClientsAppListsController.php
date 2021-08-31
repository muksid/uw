<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\UwClientApps;
use App\UwClients;
use App\UwClientGuars;
use App\UwClientsAppLists;
use App\UwGuarType;

class UwClientsAppListsController extends Controller
{

    public function index(Request $request)
    {
        $search_t = $request->search_t;
        
        $templates = UwClientApps::where('status', 'A')->orderBy('created_at','DESC')->get();

        if ($search_t) {
            $search = UwClientsAppLists::orderBy('created_at','DESC');

            $search->where('loan_id',$search_t)
                ->orWhere('client_code',$search_t)
                ->orWhere('contract_code','like','%'.$search_t.'%')
                ->orWhere('summ_loan','like','%'.$search_t.'%')
                ->orWhere('client_name','like','%'.$search_t.'%')
                ->orWhere('address','like','%'.$search_t.'%')
                ->orWhereHas('appTemplate',function ($query) use($search_t){
                    $query->where('title','like','%'.$search_t.'%');
                });

            $models = $search->paginate(25);

            $models->appends ( array (
                'search_t' => $search_t
            ));
            return view('madmin.apps.app-list', compact('models','templates','search_t'));
        }


        $models = UwClientsAppLists::orderBy('created_at','DESC')->paginate(25);


        return view('madmin.apps.app-list', compact('models','templates','search_t'));
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
        $request->validate([
            'loan_id'       => 'required',
            'client_code'   => 'required',
            'contract_code' => 'required',
            'contract_date' => 'required',
            'summ_loan'     => 'required',
            'client_name'   => 'required',
            'address'       => 'required',
            'typeof'        => 'required',
            'subject'       => 'required',
            'saldo_in_5'    => 'required',
            'filial_code'   => 'required',
            'template_id'   => 'required',
            'status'        => 'required',
        ]);

        $input = $request->all();
        
        $new = UwClientsAppLists::create($input);

        $message = "Successfully created!";

        return response()->json($message);
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
        $model = UwClientsAppLists::findOrFail($id);
        $model->delete();
        $message = "Successfully deleted!";
        return response()->json($message);
    }

    public function getUwClient(Request $request)
    {
        $data = (is_numeric($request->input('term'))) ? UwClients::where('iabs_num',$request->input('term'))->get(): [];
        $data = $request->input('term');

        if(is_numeric($data)){
            $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-jur';
    
            $data = array(
                "user" => "muksid_iabs",
                "pass" => "zz0102031!@#$",
                "id"   => $data,
                "type" => "claim"
            );
      
            $postdata = json_encode($data);
    
            $ch1 = curl_init($url);
            curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch1, CURLOPT_POST, 1);
            curl_setopt($ch1, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, 0);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $json_data = curl_exec($ch1);
            curl_close($ch1);

            $data_decode = json_decode($json_data, true);



            $saldo_in_all = 0;
            foreach ($data_decode as $key => $value) {
                if($value['loan_type_account'] == 46){
                    $saldo_in_all += $value['saldo_in'];
                }
                if($value['loan_type_account'] == 5){
                    $loan_id        = $value['loan_id'];
                    $client_code    = $value['client_code'];
                    $contract_code  = $value['contract_code'];
                    $contract_date  = $value['contract_date'];
                    $summ_loan      = $value['summ_loan'];
                    $client_name    = $value['client_name'];
                    $address        = $value['address'];
                    $typeof         = $value['typeof'];
                    $subject        = $value['subject'];
                    $filial_code    = $value['filial_code'];
                    $saldo_in_5     = $value['saldo_in']*(-1);
                }
            }
            if($saldo_in_all) $saldo_in_all = ($saldo_in_all)*(-1);

            $message = "Success";
    
            return response()->json([
                'loan_id'       => $loan_id,
                'client_code'   => $client_code,
                'contract_code' => $contract_code,
                'contract_date' => $contract_date,
                'summ_loan'     => $summ_loan,
                'client_name'   => $client_name,
                'address'       => $address,
                'typeof'        => $typeof,
                'subject'       => $subject,
                'filial_code'   => $filial_code,
                'saldo_in_5'    => $saldo_in_5,
                'saldo_in_all'  => $saldo_in_all,
                'message'       => $message,
            ],200);
        }else{
            return response()->json(null);
        }
        

        

    }
}
