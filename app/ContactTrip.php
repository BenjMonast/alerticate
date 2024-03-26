<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * The class for manipulating the contacttrip table
 * 
 * The contacttrip table maps the contact id to the trip id
 */
class ContactTrip extends Model {
    /**
     * For every contact, it inserts a row mapping it to the trip_id
     *
     * @param array $contacts
     * @param int $trip_id
     * @return void
     */
    public static function createTrip($contacts, $trip_id) {
        foreach ($contacts as $key => $c) {
            DB::table('contacttrip')->insert([
                'contact_id' => $c,
                'trip_id' => $trip_id
            ]);
        }
    }

    /**
     * Gives you a list of contact ids that are mapped to a certain trip id
     *
     * @param int $trip_id
     * @return array
     */
    public static function getSelectedContactIds($trip_id) {
        $query_res = DB::table('contacttrip')->select('contact_id')->where(['trip_id' => $trip_id])->get();
        $array = json_decode(json_encode($query_res),true);
        $contacts = array_map(function($a) {return $a['contact_id'];}, $array);
        return $contacts;
    }

    /**
     * The same as getSelectedContactIds() except this returns all information about the contact
     *
     * @param int $trip_id
     * @return array
     */
    public static function getSelectedContacts($trip_id) {
        $ids = ContactTrip::getSelectedContactIds($trip_id);
        $contacts = [];
        foreach ($ids as $id) {
            $query = DB::table('contacts')->select('*')->where('id', $id)->get();
            array_push($contacts, json_decode(json_encode($query), true));
        }
        $contacts = array_map(function($a) {return $a[0];}, $contacts);
        return $contacts;
    }
}