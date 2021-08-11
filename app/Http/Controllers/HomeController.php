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

    public $URL = "http://91.204.239.44/broker-api/send";
    private $USERNAME = "turonbank2";
    private $PASSWORD = 'e76rKR3Li2';

    public function smsSend(){

        $isError = 0;

        $errorMessage = true;

        $postData = array(
            "messages" => array([
                "recipient" => "998973253420",
                "message-id" => "mabc000000008",
                "sms" => array(
                    "originator" => "2800",
                    "content" => array(
                        "text" => "test sms Muksid 8"
                    )
                )
            ]),
        );

        $ch = curl_init($this->URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->USERNAME . ":" . $this->PASSWORD);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($ch);

        //Print error if any
        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
        }
        curl_close($ch);

        if($isError){
            return array('error' => 1 , 'message' => $errorMessage);
        }else{
            return array('error' => 0 );
        }
    }


}
