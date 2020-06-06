<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth, Session;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Jenssegers\Agent\Agent;
use App\Traits\LogsoutGuard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\UserLoginHistory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    use AuthenticatesUsers, LogsoutGuard {
    LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'login';
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function showLoginForm()
    {
        return view('backend.auth.login', ['platform' => 'admin']);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->route('admin.index');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'login' => 'required|min:2|max:30',
            'password' => 'required|min:6|max:20'
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = [
            'password' => $request->input('password')
        ];
        $login = $request->input('login');

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $login;
        } else {
            $credentials['username'] = $login;
        }

        if ($this->guard()->attempt($credentials, $request->has('remember'))) {
            $user = $this->guard()->user();
            $user->last_login = Carbon::now();
            $user->save();

            $log = new UserLoginHistory;
            $log->user_id = $user->id;
            $log->ip = getUserIP();
            $agent = new Agent;
            $log->browser = $agent->browser();
            $log->platform = $agent->platform();
            $log->save();

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
