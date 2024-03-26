<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\LoginRegister;
use App\Notification;
use Image;

class RegisterController extends Controller {
    /**
     * Shows the register page
     *
     * @param Request $request
     * @return view
     */
    public function show(Request $request) {
        $data = [];
        foreach($request->all() as $k=>$v) {
            $data[$k] = true;
        }

        return view('register', $data);
    }

    /**
     * Creates an account
     *
     * If an account already exists with the same email, it sends you back to the register page with an error
     * It also gets the user's timezone from lattitude and longitude using a web API
     * 
     * @param Request $request
     * @return redirect
     */
    public function register(Request $request) {
        print_r($request->all());
        $timezone = json_decode(
            file_get_contents("https://api.askgeo.com/v1/1962/bf9bfd22f43ce3248a7a23d01c9bfa813ab1a3559d133308901b5c3f3c1e4ae/query.json?databases=TimeZone&points=" . 
            $request->get('lat') . "," . $request->get('lon')))
            ->data[0]->TimeZone->TimeZoneId;
        $picture = $request->file('picture');
        $filename = 'default.jpg';
        if ($picture != NULL) {
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            Image::make($picture)->resize(300,300)->save(public_path('/uploads/avatars/' . $filename));
        }
        LoginRegister::createAccount($request->all() + ['timezone' => $timezone], $filename);
        return redirect('/verification');
    }

    /**
     * Tells you if an email is used
     *
     * I use this function by making a post request through ajax
     * 
     * @param Request $request
     * @return int
     */
    public function emailUsed(Request $request) {
        try {
            return (LoginRegister::checkIfEmailExists($request->get('email')) ? 'true' : 'false');
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function verify(Request $request) {
        if (LoginRegister::verifyHash($request->get('id'), $request->get('hash'))) {
            return redirect('verified')->with(['success' => 1]);
            echo '<p>Your email is verified and your account is activated</p>';
        } else {
            return redirect('verified')->with(['success' => 0]);
            echo '<p>Something went wrong</p>';
        }
    }

    public function showForgotPasswordPage() {
        return view('forgotpassword');
    }

    public function forgotPassword(Request $request) {
        LoginRegister::createForgotPasswordEntry($request->get('email'));
        return redirect('/forgotpassword/emailsent');
    }

    public function showForgotPasswordEmailSent() {
        return view('forgotpasswordemailsent');
    }

    public function resetPasswordForm(Request $request){
        $id = $request->get('id');
        $token = $request->get('token');
        if (LoginRegister::verifyResetPasswordToken($id,$token)) {
            return view('resetpassword', ['id'=>$id, 'token'=>$token]);
        } else {
            return view('errors.500');
        }
    }

    public function resetPassword(Request $request){
        $id = $request->get('id');
        $token = $request->get('token');
        $password = $request->get('password');
        if (LoginRegister::verifyResetPasswordToken($id,$token)) {
            LoginRegister::changePassword($id, $password);
            return redirect('/logout');
        } else {
            return view('errors.500');
        }
    }
}
