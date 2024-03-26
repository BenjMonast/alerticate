<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ICanBoogie\DateTime;
use App\ContactTrip;
use App\User;
use App\Trip;
use App\Profile;
use App\Contact;
use Session;

/**
 * This class simply shows views that are not associatd with any other controller
 * 
 * This class is only here so that I don't have to create functions in the web routing page
 */
class MiscViewsController extends Controller {
    /**
     * Shows the portal home page
     *
     * @return view|redirect
     */
    public function portalHome() {
        $data['hasActiveTrip'] = User::hasActiveTrip();
        if (User::hasActiveTrip()) {
            $data['activeTripInfo'] = Trip::getActiveTripInfo();
            $timezone = User::getTimeZone();
            $datetime = new DateTime($data['activeTripInfo']['end_time'], 'Etc/UCT');
            $datetime->setTimezone($timezone);
            $data['activeTripInfo']['end'] = $datetime->format("F d, Y") . " at " . $datetime->format("g:i A");
            $data['activeTripInfo']['contacts'] = ContactTrip::getSelectedContacts($data['activeTripInfo']['trip_id']);
        }
        $data['contactsAlerted'] = User::contactsAlerted();
        $data['hasTrips'] = User::hasTrips();
        $data['contacts'] = json_decode(json_encode(Contact::getContacts(), true));//json_decode converts it to an array
        $data['hasContacts'] = (count($data['contacts']) > 0? true : false);
        if (!Session::get('login')) {
            return redirect('home');
        }
        $data['userInfo'] = Profile::getUserInfo(Session::get('login'));
        return view('portal_home', $data);
    }

    /**
     * Returns the 404 error page
     * 
     * This is only here so that I have an easy way of showing 404
     *
     * @return view
     */
    public function fileNotFound() {
        return view('errors.404');
    }
    
    /**
     * Shows the home page
     *
     * @return view
     */
    public function home() {
        return view('home');
    }

    /**
     * Tells the user that they need to verify their email address
     *
     * @return view
     */
    public function verification() {
        return view('verification');
    }

    /**
     * Tells the user whether the verification was successful
     * 
     * @return view
     */
    public function verified() {
        return view('verified');
    }
}
