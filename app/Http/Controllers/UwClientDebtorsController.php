<?php

namespace App\Http\Controllers;

use App\UwClientCredits;
use App\UwClientDebtors;
use App\UwClients;
use Illuminate\Http\Request;

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
        $id = $request->debtor_id;

        $model = UwClientDebtors::updateOrCreate(['id' => $id],
            [
                'uw_clients_id' => $request->model_id,
                'inn' => $request->inn,
                'resident' => $request->resident,
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
                'isActive' => 1,
            ]);
        return response()->json([
            'success' => true,
            'result' => $model
        ]);
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
