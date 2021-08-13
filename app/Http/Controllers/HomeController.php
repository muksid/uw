<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\MPersonalUsers;
use App\MUserRoles;
use App\MWorkUsers;
use App\User;
use App\Message;
use App\UwClients;
use App\UwJuridicalClient;
use Carbon\Carbon;
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

            $user_personal = MPersonalUsers::where('user_id', $user->id)->where('doc_number', '=', null)->first();

            if ($user_personal) {

                $array = array('emp_code' => $checkUserWork->tab_num, 'type' => 'emp_upd');

                $oraEmp =  UwJuridicalClientsController::curlHttpPost($array);

                if ($oraEmp) {

                    $oraEmpDecode = json_decode($oraEmp, true);

                    $oraValue = $oraEmpDecode[0];

                    $user_personal->update([
                        'emp_id' => $oraValue['emp_id'],
                        'f_name' => $oraValue['first_name'],
                        'l_name' => $oraValue['last_name'],
                        'm_name' => $oraValue['middle_name'],
                        'birthday' => date('Y-m-d', strtotime($oraValue['birth_date'])),
                        'email' => $oraValue['mail_address'],
                        'pinfl' => $oraValue['inps'],
                        'inn' => $oraValue['inn'],
                        'doc_series' => $oraValue['passport_seria'],
                        'doc_number' => $oraValue['passport_number'],
                        'doc_begin_date' => date('Y-m-d', strtotime($oraValue['passport_date_begin'])),
                        'doc_end_date' => date('Y-m-d', strtotime($oraValue['passport_date_end'])),
                        'doc_address' => $oraValue['passport_issued'],
                        'mobile_phone' => $oraValue['phone'],
                        'address' => $oraValue['address'],
                    ]);

                }

            }

            $userInfo = MPersonalUsers::where('user_id', $user->id)->first();

            $phyClients = UwClients::where('work_user_id', $checkUserWork->id)->get()->count();

            $jurClients = UwJuridicalClient::where('work_user_id', $checkUserWork->id)->get()->count();

            $roles = MUserRoles::where('user_id', $checkUserWork->id)->get();

            $to_date = Carbon::now();
            $from_date = Carbon::createFromFormat('Y-m-d', $userInfo->doc_end_date);
            $pass_diff = $to_date->diff($from_date);

            return view('home', compact('checkUserWork','userInfo', 'phyClients', 'jurClients', 'roles', 'pass_diff'));

        }

        Auth::logout();

        return Redirect::to('/login')->with('message', $message);

    }

    public function updateUserInfo($tab_num)
    {

        $array = array('emp_code' => $tab_num, 'type' => 'emp_upd');

        $oraEmp =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraEmp) {

            $oraEmpDecode = json_decode($oraEmp, true);

            $oraValue = $oraEmpDecode[0];

            $user = Auth::user();

            $user_personal = MPersonalUsers::where('user_id', $user->id)->first();

            $user_personal->update([
                'f_name' => $oraValue['first_name'],
                'l_name' => $oraValue['last_name'],
                'm_name' => $oraValue['middle_name'],
                'birthday' => date('Y-m-d', strtotime($oraValue['birth_date'])),
                'email' => $oraValue['mail_address'],
                'pinfl' => $oraValue['inps'],
                'inn' => $oraValue['inn'],
                'doc_series' => $oraValue['passport_seria'],
                'doc_number' => $oraValue['passport_number'],
                'doc_begin_date' => date('Y-m-d', strtotime($oraValue['passport_date_begin'])),
                'doc_end_date' => date('Y-m-d', strtotime($oraValue['passport_date_end'])),
                'doc_address' => $oraValue['passport_issued'],
                'mobile_phone' => $oraValue['phone'],
                'address' => $oraValue['address'],
            ]);

        }

        return back()->with('success', 'Sizning ma`lumotlaringiz IABS tizimidan muvaffaqiyatli yangilandi');

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
