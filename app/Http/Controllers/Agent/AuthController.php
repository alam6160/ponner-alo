<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            if (isset($_POST['submit'])) {

                $validation = array(
                    'email'=> 'required|email',
                    'password'=> 'required|min:6',
                );
                $request->validate($validation);


                $email = trim($request->email);
                $password = trim($request->password);

                $user = \App\Models\User::whereEmail($email)->whereUserType('4')->first();
                if (!blank($user)) {
                    if (!empty($user->email_verified_at)) {

                        if (Hash::check($password, $user->password)) {
                            Auth::login($user);
                            $request->session()->regenerate();
                            return redirect()->route('agent.dashboard');
                        } else {
                            $message = ['The provided credentials do not match our records.', 'error'];
                            return redirect( url()->current() )->with('agent_signin', $message)->withInput();
                        }
                    } else {
                        $message = ['Please verify your email','error'];
                        return redirect( url()->current() )->with('agent_signin', $message)->withInput();
                    }
                } else {
                    $message = ['The provided credentials do not match our records.', 'error'];
                    return redirect( url()->current() )->with('agent_signin', $message)->withInput();
                }
            }
        }
        return view('agent.auth.login');
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('agent')->with('agent_signin', ['You have been logged out succcessfully!', 'success']);
        }else{
            return redirect()->route('home');
        }
    }
}
