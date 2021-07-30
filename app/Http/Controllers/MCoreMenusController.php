<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MCoreMenu;

class MCoreMenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $models = MCoreMenu::orderBy('created_at', 'DESC')->paginate(25);

        @include('count_message.php');

        return view('control.menus.m-core-menus', compact('models'))->with('i', (request()->input('page', 1) - 1) * 50);
    }

    public function search(Request $request)
    {
        # code...
        $input = $request->input('input');
        $models = MCoreMenu::where('title', 'like', '%'.$input.'%')
            ->orWhere('lang_code', 'like', '%'.$input.'%')
            ->orWhere('icon_code', 'like', '%'.$input.'%')
            ->orWhere('text_class', 'like', '%'.$input.'%')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        $models->appends ( array ('input' => $input) );

        @include('count_message.php');

        return view('control.menus.m-core-menus', compact('models','input','inbox_count','sent_count','all_inbox_count'))->with('i', (request()->input('page', 1) - 1) * 50);
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
        $this->validate($request, [
            'title'   => 'required',
            'lang_code'   => 'required',
            'isActive'    => 'required'
        ]);
        $model = MCoreMenu::create($request->all());
        $message = 'Successfuly created!';

        return response()->json(['message' => $message,'model' => $model], 200);
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
        $model = MCoreMenu::findOrFail($id);

        return response()->json($model, 200);
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
            'title'   => 'required',
            'lang_code'   => 'required',
            'isActive'    => 'required'
        ]);
        $model = MCoreMenu::findOrFail($id);

        $model->update($request->all());

        $message = 'Successfully updated';

        return response()->json(['message' => $message, 'model' => $model], 200);
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
        $ids = explode(",",$id);
        $model = MCoreMenu::whereIn('id',$ids)->get();
        $message = '';

        if($model->count() > 0){

            MCoreMenu::destroy(collect($ids));

            $message = 'Successfully deleted';
        }else{
            $message = 'Not Found';
        }

        return response()->json( $message, 200);
    }
}
