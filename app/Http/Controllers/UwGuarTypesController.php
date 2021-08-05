<?php

namespace App\Http\Controllers;

use App\UwClientGuars;
use App\UwGuarType;
use App\UwJurClientGuars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UwGuarTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        //
        $models = UwGuarType::all();

        return view('madmin.guar-types.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
        return view('madmin.guar-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        $rules = array(
            'code' => 'required|max:10',
            'title' => 'required|max:255',
            'title_ru' => 'required|max:255'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){

            return Redirect::back()->withErrors($validator)->withInput();

        } else {
            $model = new UwGuarType();
            $model->code = $request->code;
            $model->title = $request->title;
            $model->title_ru = $request->title_ru;
            $model->isActive = $request->isActive;
            $model->save();

            return Redirect::to(route('guar-type.index'))->with('success', 'Ta`minot turi qo`shildi');
        }
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

    public function getModel()
    {
        $model = UwGuarType::where('isActive', 1)->get();

        return response()->json($model);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        //
        $model = UwGuarType::findOrFail($id);

        return view('madmin.guar-types.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        //
        $rules = array(
            'code' => 'required|max:10',
            'title' => 'required|max:255',
            'title_ru' => 'required|max:255'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){

            return Redirect::back()->withErrors($validator)->withInput();

        } else {

            $request = $request->all();
            $model = UwGuarType::find($id);
            $model->update($request);

            return Redirect::to(route('guar-type.index'))
                ->with('success', 'Ta`minot turi yangilandi');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        //
        $model = UwGuarType::findOrFail($id);

        $isCheckPhy = UwClientGuars::where('guar_type', '=', $model->code)->first();

        $isCheckJur = UwJurClientGuars::where('guar_type', '=', $model->code)->first();

        if ($isCheckPhy || $isCheckJur){
            $message = 'Ta`minot turi faol foydalanilgan!!! O`chirish mumkin emas';
        } else {
            $model->delete();
            $message = 'Ta`minot turi o`chirildi';
        }
        return back()->with('success', $message);
    }
}
