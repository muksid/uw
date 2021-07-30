<?php

namespace App\Http\Controllers;

use App\UnDistricts;
use App\UnRegions;
use App\UwJurClientPersonal;
use App\UwJuridicalClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UwJurClientPersonalController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //
        $model = UwJuridicalClient::findOrFail($id);

        $personal = UwJurClientPersonal::where('jur_clients_id', $id)->first();

        $user = 'muksid_iabs';
        $pass = 'zz0102031!@#$';
        $query = "
            select a.typeof,a.phone,a.resident_code,a.region_code,a.district_code,b.* from client_current a, client_physical_current b
            where a.id = b.id and a.code = '".$model->client_code."'
            ";

        $data = array('user' => $user, 'pass' => $pass, 'query' => $query);
        $url = 'https://kpi.turonbank.uz:4343/api/ora/get-client-select';
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

        $decode_result = json_decode($result, true);

        $ora_personal = $decode_result[0];

        $regions = UnRegions::all();

        $districts = UnDistricts::all();

        return view('jur.ins.edit-personal', compact('model', 'personal', 'ora_personal', 'regions', 'districts'));
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
        $phone = preg_replace('/[^A-Za-z0-9]/', '', $request->phone);
        $rules = array(
            'document_type' => 'required|max:2',
            'document_serial' => 'required|max:2',
            'document_number' => 'required',
            'document_date' => 'required',
            'family_name' => 'required|max:100',
            'name' => 'required|max:100',
            'patronymic' => 'required|max:100',
            'pin' => 'required|max:14',
            'registration_address' => 'required',
            'live_address' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){

            return Redirect::to('/jur/client-personal/'.$id.'/edit')
                ->withErrors($validator);

        } else {
            UwJurClientPersonal::updateOrCreate(['jur_clients_id' => $id],
                [
                    'jur_clients_id' => $id,
                    'document_type' => $request->document_type,
                    'document_serial' => $request->document_serial,
                    'document_number' => $request->document_number,
                    'document_date' => $request->document_date,
                    'gender' => $request->gender,
                    'client_type' => $request->client_type,
                    'birth_date' => $request->birth_date,
                    'document_region' => $request->document_region,
                    'document_district' => $request->document_district,
                    'resident' => $request->resident,
                    'family_name' => $request->family_name,
                    'name' => $request->name,
                    'patronymic' => $request->patronymic,
                    'registration_region' => $request->registration_region,
                    'registration_district' => $request->registration_district,
                    'registration_address' => $request->registration_address,
                    'phone' => $phone,
                    'pin' => $request->pin,
                    'live_address' => $request->live_address
                ]);

            return Redirect::to('/jur/client/'.$id)
                ->with('success', 'Mijoz ma`lumotlari muvaffaqiyatli yangilandi');

        }
        //print_r($request->all()); die;
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
