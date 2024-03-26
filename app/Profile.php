<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use App\User;
use ICanBoogie\DateTime;

/**
 * This model handles the user's profile
 */
class Profile extends Model {
    /**
     * Gets the info of the current user
     * If you pass in an int, it's the id. If you pass in a string, its the email.
     *
     * @param string|int $id
     * @return array
     */
    public static function getUserInfo($id) {
        if (is_string($id)) {
            $info = DB::table('accounts')->select('*')->where('email', '=', $id)->get();
        } else {
            $info = DB::table('accounts')->select('*')->where('id', '=', $id)->get();
        }
        return json_decode(json_encode($info[0]), true);//json_decode(json_encode(stdClass), true) converts an stdClass to an array
    }

    /**
     * Updates the user's info
     *
     * @param array $info
     * @return void
     */
    public static function updateUserInfo($info) {
        extract($info);
        $old_timezone = DB::Table('accounts')->select('timezone')->where('email', Session::get('login'))->get()[0]->timezone;
        $timezone_changed = $old_timezone != $timezone;

        DB::table('accounts')->where('email', '=', Session::get('login'))->update([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'birthdate' => $birthdate,
            'country' => $country,
            'phone' => $phone,
            'lat' => $lat,
            'lon' => $lon,
            'timezone' => $timezone
        ]);

        if ($timezone_changed and User::hasActiveTrip()) {
            // Get original local time of return
            $return_time_uct = DB::Table('trips')->select('end_time')->where('active', '>', 0)->where('user_id', Session::get('id'))->get()[0]->end_time;
            $datetime = new DateTime($return_time_uct, 'Etc/UCT');
            $datetime->setTimezone($old_timezone);
            $return_time_local = $datetime->format('Y-m-d H:i:s');
            $datetime = new DateTime($return_time_local, $timezone);
            $datetime->setTimezone('Etc/UCT');
            $new_time = $datetime->format('Y-m-d H:i:s');
            // Add new time to database
            DB::table('trips')->where('active', '>', 0)->where('user_id', Session::get('id'))->update([
                'end_time' => $new_time
            ]);
        }
    }

    public static function changePicture($filename) {
        DB::table('accounts')->where('email', '=', Session::get('login'))->update([
            'picture' => $filename
        ]);
    }
}
