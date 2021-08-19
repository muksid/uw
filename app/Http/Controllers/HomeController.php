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
        //print_r($user); die;
        $user = Auth::user();

        $checkUserWork = MWorkUsers::where('user_id', Auth::id())->where('isActive', '=', 'A')->first();

        if ($user->isActive == 'P'){

            $message = 'Xodim tizimda passiv holatda!!!';

        } elseif ($user->isActive == 'D'){

            $message = 'Xodim tizimdan o`chirilgan!!!';

        } elseif (!$checkUserWork) {

            $message = 'Xodim faol ish joyi topilmadi!!!';

        } elseif ($user->cb_id == 0) {

            $message = 'CB 0 Adminstratorga muroojat qiling!!!';

        } else {

            $user_personal = MPersonalUsers::where('user_id', $user->id)->where('doc_number', '=', null)->first();

            if ($user_personal) {

                $array = array('cb_id' => $user->cb_id, 'type' => 'emp_upd_personal');

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

            return view('home', compact('checkUserWork','userInfo', 'phyClients', 'jurClients', 'roles', 'pass_diff', 'user'));

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

    public function updateUserCBIds()
    {
        print_r('not'); die;

        $user = User::leftJoin('m_work_users', function($join) {
            $join->on('users.id', '=', 'm_work_users.user_id')
                ->where('m_work_users.isActive', '=', 'A');
        })
            ->where('users.isActive', '=', 'A')
            ->where('users.cb_id', '=', '')
            //->where('m_work_users.branch_code', '!=', '00982')
            //->where('m_work_users.emp_id', '=', '10385')
            ->get();
        //print_r($user); die;

        $array = array('filial' => '09011', 'type' => 'emp_get_cb_ids');

        $oraEmp =  UwJuridicalClientsController::curlHttpPost($array);

        if ($oraEmp) {
            $oraEmpDecode = json_decode($oraEmp, true);
            if ($oraEmpDecode) {

                foreach ($oraEmpDecode as $item) {
                    //print_r($item); die;
                    $ss = User::where('tab_num', '=', $item['tab_num'])->first();


                    if ($ss){
                        //print_r($oraValue['cb_id']); die;
                        //print_r($ss);

                        $ss->update(['cb_id' => $item['cb_id']]);
                    }
                }
            }

        }

    }


}
