<?php

namespace App;

use Session;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * This model is for giving information about the user
 */
class User extends Authenticatable {
    /**
     * Gets the id of the email
     *
     * @param string $email
     * @return int
     */
    public static function getId($email) {
        return DB::Table('accounts')->select('id')->where('email', '=', $email)->get()[0]->id;
    }

    /**
     * Gets the email of the user id
     *
     * @param int $id
     * @return string
     */
    public static function getEmail($id) {
        return DB::Table('accounts')->select('email')->where('id', '=', $id)->get()[0]->email;
    }

    /**
     * Tells weather or not the user has an active trip
     *
     * @return boolean
     */
    public static function hasActiveTrip() {
        $results = DB::Table('trips')->select('*')->where('user_id', '=', Session::get('id'))->get();
        $results = json_decode(json_encode($results), true);
        $results = array_map(function($i) {return $i['active'];}, $results);
        $results = in_array(1, $results);
        $results = $results ? true : false;
        return $results;
    }
    
    /**
     * Tells weather or not the contacts have been alerted
     *
     * @return boolean
     */
    public static function contactsAlerted() {
        $results = DB::Table('trips')->select('active')->where('user_id', '=', Session::get('id'))->get();
        $results = json_decode(json_encode($results), true);
        $results = array_map(function($i) {return $i['active'];}, $results);
        $results = in_array(2, $results);
        $results = $results ? true : false;
        return $results;
    }
    
    /**
     * Tells weather or not the user has any trips
     *
     * @return boolean
     */
    public static function hasTrips() {
        $results = DB::Table('trips')->select('trip_id')->where('user_id', '=', Session::get('id'))->get();
        $bool = count($results) == 0 ? false : true;
        return $bool;
    }

    /**
     * Gets the timezone of the user
     *
     * @return string
     */
    public static function getTimeZone($id=NULL) {
        if ($id == NULL) {
            $id = Session::get('id');
        }
        $result = DB::Table('accounts')->select('timezone')->where('id', $id)->get();
        $timezone = json_decode(json_encode($result),true)[0]['timezone'];
        return $timezone;
    }

    /**
     * Tells if the user is logged in
     *
     * @return bool
     */
    public static function loggedIn() {
        return Session::get('login') != null;
    }

    /**
     * Tells if the user is verified
     *
     * @param int $id
     * @return boolean
     */
    public static function isVerified($id) {
        return boolval(DB::Table('accounts')->select('active')->where('id', $id)->get()[0]->active);
    }
}
