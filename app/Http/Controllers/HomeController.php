<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\User;
use App\Message;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {

        return view('home');
    }

    public function storageLog()
    {

        return view('storage');
    }

}
