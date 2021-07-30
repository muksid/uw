<?php

namespace App\Http\Controllers;

use App\UwJurBalanceChild;
use App\UwJurBalanceForm;
use App\UwJurFinancialChild;
use App\UwJurFinancialForm;
use App\UwJurKatmFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UwJurBalanceFormsController extends Controller
{


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalanceModal(Request $request)
    {
        //
        $id = $request->id;

        $type = $request->type;

        if ($type == 'b'){
            $model = UwJurBalanceForm::where('uw_jur_clients_id', $id)->where('isActive', 1)->first();

            $child = UwJurBalanceChild::where('uw_jur_balance_id', $model->id)->get();

        } elseif ($type == 'fb') {

            $model = UwJurFinancialForm::where('uw_jur_clients_id', $id)->where('isActive', 1)->first();

            $child = UwJurFinancialChild::where('uw_jur_financial_id', $model->id)->get();

        }

        return response()->json([
            'type' => $type,
            'balance' => $model,
            'child' => $child
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
