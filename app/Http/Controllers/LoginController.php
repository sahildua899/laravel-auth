<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Validator;

class LoginController extends Controller
{
    // Login Page for customer and general user
    public function index() {
        return view('login');
    }
    //
    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password'=>'required'
        ]);

        if($validator->passes()){
            if(Auth::attempt(['email'=> $request->email, 'password'=>$request->password])) {
                return redirect()->route('account.dashboard');
            }else {
                return redirect()->route('account.login')->with('error', 'Either Email or Password is Incorrect');
            }
        }else{
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
    }

    public function register() {
        return view('register');
    }

    // register account
    public function processRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email' => 'required|email|unique:users',
            'password'=>'required|confirmed|min:5',
            'password_confirmation'=>'required',
        ]);

        if($validator->passes()){
            
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'customer';
            $user->save();
            return redirect()->route('account.login')->with('success', 'Registered Successfully');
        }else{
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
    }

    public function logout () {
        Auth::logout();
        return redirect()->route('account.login');
    }
}
