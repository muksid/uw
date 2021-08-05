<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\MWorkUsers;
use App\User;
use App\Message;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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

    public function index()
    {
        $user = Auth::user();

        $checkUserWork = MWorkUsers::where('user_id', Auth::id())->where('isActive', '=', 'A')->first();

        if ($user->status == 0){

            $message = 'Xodim tizimda passiv holatda!!!';

        } elseif ($user->status == 2){

            $message = 'Xodim tizimdan o`chirilgan!!!';

        } elseif ($user->isUw == 0){

            $message = 'Xodim tizimdan topilmadi!!!';

        } elseif (!$checkUserWork) {

            $message = 'Xodim faol ish joyi topilmadi!!!';

        } else {

            return view('home');
        }

        Auth::logout();

        return Redirect::to('/login')->with('message', $message);

    }

    public function storageLog()
    {
        //
        return view('storage');
    }

    public function getLogFile()
    {
        //
        $pathToFile = storage_path() . "/logs/laravel.log";

        return response()->file($pathToFile);
    }

    public function cacheClear($type)
    {
        //
        if ($type == 'route')
        {

            Artisan::call('route:clear');
            $message = 'Route cleared';

        }
        elseif ($type == 'config')
        {

            Artisan::call('config:clear');
            $message = 'Config cleared';

        }
        elseif ($type == 'clear')
        {

            Artisan::call('cache:clear');
            $message = 'Cache cleared';

        }
        elseif ($type == 'view')
        {

            Artisan::call('view:cache');
            $message = 'View cleared';

        } else {

            $message = 'not found';

        }

        return back()->with('message', $message);
    }

}
