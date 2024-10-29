<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Business;
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

    public function showLoginForm()
    {

        return view('auth.login');
    }

    public function AdminLogin()
    {

        return view('backend.admin-login');
    }

    public function AdminLoginStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if(Auth::guard('admin')->attempt($request->only('email','password'))){

            return redirect('/admin/dashboard');
        }

        return redirect()->back()->withErrors(['email'=>'invalid crediential']);
    }

    function Admindashboard(){
        return view('backend.admin-dashboard');
    }

    function BusinessLogin($id){
       $business=Business::find($id);
       $user=User::find($business->owner_id);
        Auth::guard('web')->login($user);
        return redirect('/home');
    }


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home?set_lang=1';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
