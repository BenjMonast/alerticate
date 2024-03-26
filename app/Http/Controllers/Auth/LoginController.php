<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\LoginRegister;
use Session;

class LoginController extends Controller {
    /**
     * Logs out the user
     *
     * @return redirect
     */
    public function logout() {
        Session::forget('login');
        Session::forget('id');
        Session::forget('name');
        return redirect('/home');
    }

    /**
     * Shows the login page
     *
     * @return view
     */
    public function show() {
        return view('login');
    }

    /**
     * Logs in the user
     *
     * @param Request $request
     * @return view|redirect
     */
    public function login(Request $request) {
        return LoginRegister::logIn($request->get('username'), $request->get('password'));
    }
}
