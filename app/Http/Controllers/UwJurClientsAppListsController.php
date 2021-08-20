<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UwClientApps;
use App\UwJuridicalClient;
use App\UwClientGuars;
use App\UwClientsAppLists;
use App\UwGuarType;

class UwJurClientsAppListsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_t = $request->search_t;

        $templates = UwClientApps::where('type','J')->where('status', 'A')->orderBy('created_at','DESC')->get();
        
        if ($search_t) {
            $search = UwClientsAppLists::where('client_type','jur');

            $search->whereHas('uwPhyClients',function ($query) use($search_t)
            {
                $query->where('iabs_num','like',$search_t);
                $query->orWhereRaw("concat(family_name, ' ', name,' ',patronymic) like '%".$search_t."%' ");
                $query->orWhere('inn',$search_t);
                $query->orWhere('claim_id',$search_t);
                $query->orWhere('summa','like','%'.$search_t.'%');
                $query->orWhere('branch_code',$search_t);
            })
            ->orWhereHas('appTemplate',function ($query) use($search_t)
            {
                $query->where('type','J')->where('title','like','%'.$search_t.'%');
            });

            $models = $search->orderBy('created_at','DESC')->paginate(25);

            $models->appends ( array (
                'search_t' => $search_t
            ));
            
            return view('madmin.apps.app-list', compact('models','templates','search_t'));
        }

        $models = UwClientsAppLists::where('client_type','jur')->orderBy('created_at','DESC')->paginate(25);

        return view('madmin.apps.app-list-jur', compact('models','templates','search_t'));
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
        $request->validate([
            'uw_client_id'  => 'required',
            'client_type'   => 'required',
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
        //
    }

    public function getJurUwClient(Request $request)
    {
        $data = (is_numeric($request->input('term'))) ? UwJuridicalClient::where('claim_id',$request->input('term'))->get(): [];
        
        return response()->json($data);    
    }
}
