<?php

namespace App\Http\Controllers;

use App\Department;
use App\Filials;
use App\UwClients;
use App\UwLoanBank;
use App\UwLoanTypes;
use Illuminate\Http\Request;
use Redirect;
use Response;

class UwLoanTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        //
        if(request()->ajax())
        {
            return datatables()->of(UwLoanTypes::get())
                ->addColumn('action', function($data){
                    //$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post "><i class="fa fa-pencil"></i></a>';
                    //$button .= '&nbsp;&nbsp;';
                    $button = '<a href="javascript:void(0);" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'
                                " class="delete btn btn-danger flat"><i class="fa fa-trash"></i></a>';
                    $button .= '&nbsp;&nbsp;';

                    $button .= '<a href="javascript:void(0);" id="passive-post" data-toggle="tooltip" data-original-title="Passive" data-id="'.$data->id.'
                                 " class="btn btn-primary flat"><i class="fa fa-check-circle-o"></i></a>';
                    $button .= '&nbsp;&nbsp;';

                    $button .= '<a href="javascript:void(0);" id="banks-post" data-toggle="tooltip" data-original-title="Banks" data-id="'.$data->id.' data-rent="'.$data->id.'
                                 " class="btn btn-info flat"><i class="fa fa-bank"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('madmin.loan-types.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBanks($id)
    {
        //
        $loanName = UwLoanTypes::find($id);

        $checkedBanks = UwLoanBank::where('loan_types_id', $id)->get();

        $enableBanks = $checkedBanks->implode('depart_id', ',');

        $enableBanks = explode(',', $enableBanks);

        $checkedModel = Department::whereIn('id', $enableBanks)->get();

        $model = Department::whereNotIn('id', $enableBanks)->where('parent_id', 0)->get();


        return response()->json(['model' => $model, 'checkedModel' => $checkedModel, 'enableBanks' => $enableBanks, 'loanName' => $loanName]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeBanks(Request $request)
    {
        //
        $loan_id = $request->loan_id;
        $filials = $request->filial_id;

        $deleteOld = UwLoanBank::where('loan_types_id',$loan_id);
        $deleteOld->delete();

        if ($filials){
            foreach ($filials as $filial)
            {
                $model = new UwLoanBank();
                $model->loan_types_id = $loan_id;
                $model->filials_id = $filial;
                $model->depart_id = $filial;
                $model->isActive = 1;
                $model->save();
            }
        }

        return response()->json($request->all());
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
        $postId = $request->post_id;

        $post = UwLoanTypes::updateOrCreate(['id' => $postId],
            [
                'title' => $request->title,
                'credit_type' => $request->credit_type,
                'procent' => $request->procent,
                'credit_duration' => $request->credit_duration,
                'credit_exemtion' => $request->credit_exemtion,
                'currency' => $request->currency,
                'dept_procent' => $request->dept_procent,
                'short_code' => $request->short_code,
                'isActive' => $request->isActive
            ]);

        return Response::json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $model = UwLoanTypes::find($id);

        $modelBank = UwLoanBank::where('loan_types_id', $id)->get();

        if ($model->isActive == 1){
            $status = 0;
            if ($modelBank){
                foreach ($modelBank as $value){
                    $value->update(['isActive' => $status]);
                }
            }
        } elseif ($model->isActive == 0){
            $status = 1;
            if ($modelBank){
                foreach ($modelBank as $value){
                    $value->update(['isActive' => $status]);
                }
            }
        }

        $model->update(['isActive' => $status]);

        return response()->json(['message' => 'success']);
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
        $where = array('id' => $id);
        $post  = UwLoanTypes::where($where)->first();

        return Response::json($post);
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
        $checkClient = UwClients::where('loan_type_id', $id)->first();
        if(!$checkClient){
            //print_r($id); die;

            //UwLoanTypes::where('id',$id)->delete();

            return Response::json([
                'message'=>'Client deleted',
                'code'=>200
            ]);
        }
        return Response::json([
            'message'=>'Check Client not deleted!',
            'code'=>201
        ]);
    }
}
