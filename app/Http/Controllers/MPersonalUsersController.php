<?php

namespace App\Http\Controllers;

use App\MPersonalUsers;
use Illuminate\Http\Request;
use App\User;


class MPersonalUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $models = MPersonalUsers::orderBy('created_at', 'DESC')->paginate(25);
        @include('count_message.php');

        return view('control.users.m-personal-users', compact('models'))
            ->with('i', (request()->input('page', 1) - 1) * 50);
    }

    public function search(Request $request)
    {
        $input = $request->input('input');
        $models = MPersonalUsers::where('id', $input)
            ->orWhere('f_name', 'like', '%'.$input.'%')
            ->orWhere('l_name', 'like', '%'.$input.'%')
            ->orWhere('s_name', 'like', '%'.$input.'%')
            ->orWhere('address', 'like', '%'.$input.'%')
            ->orWhere('birthday', 'like', '%'.$input.'%')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);


        $models->appends ( array ('input' => $input) );

        @include('count_message.php');

        return view('control.users.m-personal-users', compact('models','input','inbox_count','sent_count','all_inbox_count'))
            ->with('i', (request()->input('page', 1) - 1) * 50);
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
     * @param  \App\MPersonalUsers  $mPersonalUsers
     * @return \Illuminate\Http\Response
     */
    public function show(MPersonalUsers $mPersonalUsers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MPersonalUsers  $mPersonalUsers
     * @return \Illuminate\Http\Response
     */
    public function edit(MPersonalUsers $mPersonalUsers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MPersonalUsers  $mPersonalUsers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MPersonalUsers $mPersonalUsers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MPersonalUsers  $mPersonalUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy(MPersonalUsers $mPersonalUsers)
    {
        //
    }

    public function emailCheck($email)
    {
        $user = MPersonalUsers::where('email', $email)->first();

        return response()->json($user, 200);
    }
}
