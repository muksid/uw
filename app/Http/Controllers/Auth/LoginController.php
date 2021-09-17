<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\MWorkUsers;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';


        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        {

            $checkUser = User::where('username', '=',$input['username'])->first();
            $checkUserWork = MWorkUsers::where('user_id', Auth::id())->where('isActive', '=', 'A')->first();

            if ($checkUser->isActive == 'P') {
                $message = 'Xodim tizimda passiv holatda!!!';

            } elseif ($checkUser->isActive == 'D') {
                $message = 'Xodim tizimdan o`chirilgan!!!';

            } elseif ($checkUser->isActive == 'H') {
                $message = 'Inspektor topilmadi!!!';

            } elseif ($checkUser->cb_id == 0) {
                $message = 'CB 0 Adminstratorga muroojat qiling!!!';

            } elseif (!$checkUserWork) {
                $message = 'Xodim active pozitsiyasi topilmadi!!!';

            } elseif ($checkUser->isActive == 'A') {
                return redirect()->route('home');

            }
            //print_r($message); die;
            Auth::logout();
            return redirect()->route('login')
                ->with('message', $message);

        }
        else{
            return redirect()->route('login')
                ->with('message','Login yoki kalit so`z mos kelmadi');
        }

    }
}
