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

    public function index()
    {
        //
        $models = UwClientsAppLists::orderBy('updated_at','DESC')->paginate(25);

        $templates = UwClientApps::where('type','P')->where('status', 'A')->orderBy('created_at','DESC')->get();

        $guar_types = UwGuarType::get();
        return view('madmin.apps.app-list', compact('models','templates','guar_types'));
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
            'uw_client_id'  => 'required',
            'guar_type_id'  => 'required',
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
        // $data = [];
        // dd($request->input('term'));
        if(is_numeric($request->input('term'))){
            $data = UwClients::where('iabs_num',$request->input('term'))->get();    
        }else {
            $data = [];
        }
        return response()->json($data);
    }
}
