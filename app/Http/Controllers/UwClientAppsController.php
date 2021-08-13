<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UwClientApps;
use App\UwClients;
use App\UwClientGuars;

class UwClientAppsController extends Controller
{
    public function index()
    {
        //
        $models = UwClientsAppLists::orderBy('created_at')->get();
        dd($model);
        return view('madmin.apps.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('madmin.apps.create');
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
        $this->validate($request, [
            'title'     => 'required|max:512',
            'type'      => 'required',
            'body'      => 'required',
            'status'    => 'required',
        ]);

        $claim = UwClientApps::create($request->all());
        return redirect()->route('app.show',['id' => $claim->id])->with('message', 'Record Successfully Stored!');
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
        $model = UwClientApps::findOrFail($id);

        return view('madmin.apps.show', compact('model'));
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
        $model = UwClientApps::findOrFail($id);
        return view('madmin.apps.edit',compact('model'));
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
        $this->validate($request, [
            'title'     => 'required|max:512',
            'type'      => 'required',
            'body'      => 'required',
            'status'    => 'required',
        ]);
        $model = UwClientApps::findOrFail($id);
        $input = $request->all();
        $model->update($input);
        
        return redirect()->route('app.show',['id' => $id])->with('message', 'Record Successfully Updated!');
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
        $model = UwClientApps::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Record Successfully Deleted!']);
    }

    public function appGetPhyTemplate($id,$template_id)
    {
        $model = UwClients::findOrFail($id);
        $guard = UwClientGuars::where('uw_clients_id',$id)->get();
        $app = UwClientApps::find($template_id);
        
        return view('madmin.apps.temp-phy', compact('model','app','guard'));
    }

}
