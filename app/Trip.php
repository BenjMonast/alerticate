<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;
use App\User;
use App\ContactTrip;
use App\Notification;
use App\Profile;

/**
 * This is a model for handling trips
 */
class Trip extends Model {
    /**
     * Creates a trip
     *
     * @param array $info
     * @return void
     */
    public static function create($info) {
        extract($info);
        DB::table('trips')->insert([
            'user_id' => Session::get('id'),
            'destination_lat' => $lat,
            'destination_lon' => $lon,
            'destination_address' => $formatted_address
        ]);
    }

    /**
     * Updates the trip's information and edits the contacttrip table
     *
     * @param array $info
     * @return void
     */
    public static function edit($info) {
        extract($info);
        $previouslySelectedContacts = ContactTrip::getSelectedContactIds($id);
        DB::table('trips')->where('trip_id', '=', $id)->update([
            'name' => $trip_name,
            'note' => $note,
            'destination_lat' => $lat,
            'destination_lon' => $lon,
            'destination_address' => $formatted_address
        ]);
        foreach ($previouslySelectedContacts as $contact_id) {
            if (!in_array($contact_id, $contacts)) {// If the previously selected contact is not currently selected, remove it from the contacttrip table
                DB::table('contacttrip')->where('contact_id', $contact_id)->delete();
            }
        }
        try {
            foreach ($contacts as $contact_id) {
                if (!in_array($contact_id, $previouslySelectedContacts)) {// If the selection is not previously selected, that means that it's new
                    DB::table('contacttrip')->insert([
                        'contact_id' => $contact_id,
                        'trip_id' => $id
                    ]);
                }
            }
        } catch(Exception $e) { unset($e); }
    }

    /**
     * Gets the trips associated with the current user
     *
     * @return array
     */
    public static function getTrips() {
        $trips = DB::table('trips')
            ->select('trip_id','destination_lat', 'destination_lon', 'destination_address', 'name', 'note')
            ->where('user_id', '=', Session::get('id'))->get();
        return json_decode(json_encode($trips), true);
    }

    /**
     * Gets the trip by the trip id
     *
     * @param int $id
     * @return array
     */
    public static function getTripInfo($id) {
        $trips = DB::table('trips')
            ->select('destination_lat', 'destination_lon', 'destination_address', 'name', 'note')
            ->where('trip_id', '=', $id)->first();
        return json_decode(json_encode($trips), true);
    }

    /**
     * Removes the trip and all contacttrip rows associated with it
     *
     * @param int $id
     * @return void
     */
    public static function remove($id) {
        DB::table('trips')->where('trip_id', '=', $id)->delete();
        DB::table('contacttrip')->where('trip_id', $id)->delete();
    }

    /**
     * Checks if the trip belongs to the user
     *
     * @param int $trip_id
     * @param int $user_id
     * @return bool
     */
    public static function tripIdValid($trip_id, $user_id) {
        $result = DB::table('trips')->select('user_id')->where('trip_id', '=', $trip_id)->get();
        if(!count($result)) {//If the trip_id doesn't exist
            return false;
        }
        if ($user_id !== $result[0]->user_id) {
            return false;
        }
        return true;
    }

    /**
     * Starts the trip
     *
     * @param int $id
     * @param string $end_time
     * @param string $start_date
     * @return void
     */
    public static function startTrip($id, $end_time, $start_date, $email) {
        if ($email) {
            $name = Profile::getUserInfo(Session::get('id'))['firstname'];
            $body = "This is an automated message to tell you that $name has started his trip";
            Notification::notify($id, "$name has started his trip", $body, $body);
        }
        DB::Table('trips')
            ->where('trip_id', $id)
            ->update(['active' => 1, 'end_time' => $end_time]);
    }

    /**
     * Gets information about the currently active trip
     *
     * @return void
     */
    public static function getActiveTripInfo() {
        $info = DB::Table('trips')
            ->where('user_id', Session::get('id'))
            ->where('active', '>', 0)
            ->get()[0];
        $info = json_decode(json_encode($info), true);
        return $info;
    }

    /**
     * Gets the id of the last trip associated with the current user
     *
     * @return null|int
     */
    public static function getLastTripId() {
        $object = DB::Table('trips')
            ->select('trip_id')
            ->where('user_id', Session::get('id'))
            ->get();
        $array = json_decode(json_encode($object), true);
        if (count($array) == 0) {
            return NULL;
        }
        $id = array_slice($array, -1)[0]['trip_id'];
        return $id;
    }

    /**
     * Changes the trips status to 0
     *
     * @return void
     */
    public static function returned() {
        $trip_id = DB::Table('trips')
            ->select('trip_id')
            ->where('user_id', Session::get('id'))
            ->where('active', '>','0')
            ->get()[0]
            ->trip_id;        

            
        $name = Profile::getUserInfo(Session::get('id'))['firstname'];
        $subject = "$name has safely returned";
        $body = "This is an automated message to tell you that $name has safely returned from his trip";
            
        Notification::notify($trip_id, $subject, $body, $body);

        Trip::remove($trip_id);
    }

    /**
     * Marks the trip as inactive
     *
     * @return void
     */
    public static function over() {
        $id = DB::Table('trips')
            ->where('user_id', Session::get('id'))
            ->where('active', '>', '0')
            ->value('trip_id');
        echo $id;
        Trip::remove($id);
    }
}
