<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;
use App\User;

/**
 * This class makes queries for the contacts table
 */
class Contact extends Model {
    /**
     * Gets the contacts of the current user
     *
     * @return stdClass
     */
    public static function getContacts() {
        $id = Session::get('id');
        $contacts = DB::Table('contacts')->select('*')->where('user_id', '=', $id)->get();
        return $contacts;
    }

    /**
     * Updates the contacts
     *
     * @param array $newContacts
     * @return void
     */
    public static function updateContacts($newContacts) {
        unset($newContacts['_token']);//Don't want to iterate through the token
        $id = Session::get('id');
        $oldContacts = DB::table('contacts')->select('*')->where('user_id', $id)->get();
        $oldContacts = json_decode(json_encode($oldContacts), true);
        $oldContactIds = DB::table('contacts')->select('*')->where('user_id', $id)->get();
        $oldContactIds = array_map(function ($a) {return $a['id'];}, json_decode(json_encode($oldContactIds), true));
        var_export($newContacts);
        $newContactIds = array_map(function ($a) {return $a['id'];}, json_decode(json_encode($newContacts), true));

        foreach ($newContactIds as $count=>$id) {
            // echo "<br>Count: $co
            if (in_array($id, $oldContactIds)) {// If the new contact exists in the list of old contacts, the contact needs editing
                $contact = $newContacts[$count];
                DB::table('contacts')->where('id', $contact['id'])->update([
                    'firstname' => $contact['firstname'],
                    'lastname' => $contact['lastname'],
                    'country' => $contact['country'],
                    'phone' => $contact['phone'],
                    'email' => $contact['email'],
                ]);
            }
            if ($id < 0) {// If the id is less than 0, it is a new contact
                $contact = $newContacts[$id];
                DB::table('contacts')->insert([
                    'user_id' => Session::get('id'),
                    'firstname' => $contact['firstname'],
                    'lastname' => $contact['lastname'],
                    'country' => $contact['country'],
                    'phone' => $contact['phone'],
                    'email' => $contact['email'],
                ]);
            }
        }

        foreach ($oldContactIds as $count => $id) {
            if (!in_array($id, $newContactIds)) {// If the old contact can't be found in the list of new contacts, it must be deleted
                DB::table('contacts')->where('id', $id)->delete();
                DB::table('contacttrip')->where('contact_id', $id)->delete();
            }
        }
    }
}
