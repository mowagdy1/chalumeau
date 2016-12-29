<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\CssSelector\Tests\Parser\ReaderTest;

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
    protected $redirectTo = '/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getDashboardLogin(){
        if (Auth::check()){
            if (Auth::user()->role=='admin'){
                return redirect('dashboard');
            }
            return redirect('/');
        }
        return view('dashboard.login');
    }

    public function postDashboardLogin(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required',
        ]);
        $email=$request['email'];
        $password=$request['password'];
        $remember=$request['remember'];
        if (Auth::attempt(['email'=> $email,'password'=>$password],$remember)){
            if (Auth::user()->role=='admin'){
                return redirect('dashboard');
            }
            return redirect('/')->with('message', 'You do not have permission to access dashboard!')->with('class', 'alert-danger');
        }
        return back()->with('message', 'Check your email and password!')->with('class', 'alert-danger');
    }
}
