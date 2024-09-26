<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Validator;

class LoginController extends Controller
{
    public function index() {
        return view('admin.login');
    }
    //

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password'=>'required'
        ]);

        if($validator->passes()){
            if(Auth::guard('admin')->attempt(['email'=> $request->email, 'password'=>$request->password])) {
                if(Auth::guard('admin')->user()->role != "admin") {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'You are not Authorised to access this page');
                }
                return redirect()->route('admin.dashboard');
            }else {
                return redirect()->route('admin.login')->with('error', 'Either Email or Password is Incorrect');
            }
        }else{
            return redirect()->route('admin.login')->withInput()->withErrors($validator);
        }
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
