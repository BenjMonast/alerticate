<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Notification;
use App\Profile;
use Session;

/**
 * This model handles the backend of logging in and registering
 */
class LoginRegister extends Model {
    /**
     * Logs in the user
     *
     * @param string $username
     * @param string $password
     * @return view|redirect
     */
    public static function logIn($username, $password) {
        $result = DB::Select('SELECT * FROM accounts WHERE email = "' . $username . '"');
        if (count($result) === 0) {
            return redirect('/home')->with('error', 'Wrong Email or Password');
        }
        $hash_pwd = $result[0]->hash_pwd;
        $id = User::getId($username);
        if (password_verify($password, $hash_pwd)) {
            Session(['login' => $username, 'id' => $id, 'name' => Profile::getUserInfo($id)['firstname']]);
            return redirect('/');
        } else {
            return redirect('/home')->with('error', 'Wrong Email or Password');
        }
    }

    /**
     * Tells you if the email already exists
     *
     * @param string $email
     * @return bool
     */
    public static function checkIfEmailExists($email) {
        $emails = DB::table('accounts')->pluck('email')->toArray();
        return in_array($email, $emails);
    }

    /**
     * Creates an account
     *
     * @param array $info
     * @return void
     */
    public static function createAccount($info,$filename) {
        $hash = md5(rand(0,1000));
        extract($info);
        DB::table('accounts')->insert([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'hash_pwd' => Hash::make($password),
            'birthdate' => $birthdate,
            'country' => $country,
            'phone' => $phone,
            'lat' => $lat,
            'lon' => $lon,
            'timezone' => $timezone,
            'picture' => $filename,
            'hash' => $hash
        ]);
        Notification::sendVerificationEmail($email,$hash);
        Session(['email' => $email]);
    }

    public static function verifyHash($id, $hash) {
        $realHash = DB::table('accounts')->where('id', $id)->value('hash');
        if ($hash == $realHash) {
            DB::table('accounts')->where('id',$id)->update(['active' => 1]);
            return true;
        } else {
            return false;
        }
    }
    
    public static function createForgotPasswordEntry($email) {
        $id = User::getId($email);
        $token = md5(rand(0,1000));
        DB::table('forgotpassword')->insert([
            'user_id' => $id,
            'token' => $token
        ]);
        $body="Link: " . url('passwordreset') . "?id=$id&token=$token";
        Notification::sendEmail([$email],'Reset Your Password',$body);
    }

    public static function verifyResetPasswordToken($user_id, $token) {
        $tokens = DB::table('forgotpassword')->where('user_id',$user_id)->get(['token']);
        $tokens = json_decode(json_encode($tokens),true);
        $tokens = array_map(function ($x) {return $x['token'];}, $tokens);

        return in_array($token,$tokens);
    }

    public static function changePassword($user_id, $password) {
        DB::Table('accounts')->where('id',$user_id)->update([
            'hash_pwd' => Hash::make($password)
        ]);
    }
}