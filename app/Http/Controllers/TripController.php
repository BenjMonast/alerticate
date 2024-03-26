<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ICanBoogie\DateTime;
use Session;
use App\ContactTrip;
use App\Contact;
use App\Trip;
use App\User;
use App\Profile;
use App\Email;
use App\Notification;

/**
 * Controller for handling trips
 * 
 * If you try to access any of the view pages without logging in, it will redirect you
 */
class TripController extends Controller {
    /**
     * Shows the page to create a trip
     *
     * @return view
     */
    public function show() {
        if (count(Contact::getContacts()) == 0) {return view('getContacts');}
        $data['id'] = Trip::getLastTripId() + 1;
        $data['contacts'] = Contact::getContacts();
        $data['timezone'] = User::getTimezone();
        return view('create_trip', $data);
    }

    /**
     * Shows the edit trip page
     *
     * This function returns the 404 page if you try to edit a trip that isn't yours
     * 
     * @param Request $request This function takes in the request in order to get the id of the trip you are editing
     * @return view
     */
    public function showEdit(Request $request) {
        if (count(Contact::getContacts()) == 0) {return view('getContacts');}
        $id = $request->query('id');
        if (!Trip::tripIdValid($id, Session::get('id'))) {
            return view('errors.404');
        }
        $data = Trip::getTripInfo($id);
        $data['id'] = $id;
        $data['contacts'] = Contact::getContacts();
        $data['timezone'] = User::getTimezone();
        $data['selectedContactIds'] = ContactTrip::getSelectedContactIds($id);
        return view('edit_trip', $data);
    }

    /**
     * Shows the page that displays all of your trips
     *
     * @return view
     */
    public function showList() {
        if (count(Contact::getContacts()) == 0) {return view('getContacts');}
        $data['trips'] = Trip::getTrips();
        $data['firstname'] = Profile::getUserInfo(Session::get('login'))['firstname'];
        return view('trips', $data);
    }

    /**
     * Shows the form to start the trip
     *
     * This function returns the 404 page if you try to start a trip that isn't yours
     * 
     * @param Request $request
     * @return view
     */
    public function showStartTripForm(Request $request) {
        if (count(Contact::getContacts()) == 0) {return view('getContacts');}
        $id = $request->query('id');
        $contacts = ContactTrip::getSelectedContacts($id);
        if (!Trip::tripIdValid($id, Session::get('id'))) {
            return view('errors.404');
        }
        return view('start_trip_form', ['id' => $id, 'timezone' => User::getTimezone(), 'contacts' => $contacts]);
    }

    /**
     * Edits the trip
     * 
     * If the user wants to start the trip immediately after editing trip, it converts the end datetime into UTC, and starts the trip.
     *
     * @param Request $request
     * @return redirect
     */
    public function edit(Request $request) {
        Trip::edit($request->all());
        if ($request->get('active') == 1) {
            $timezone = User::getTimeZone();
            $datetime = $request->get('end-date') . ' ' . $request->get('end-time');
            $datetimeobj = new DateTime($datetime, $timezone);
            $datetime = $datetimeobj->utc->format("Y-m-d H:i:s");
            Trip::startTrip(Trip::getLastTripId(), $datetime, $request->get('start-date'), $request->get('email'));
            return redirect('/');
        }
        return redirect('/trips');
    }

    /**
     * Creates the trip
     * 
     * If the user wants to start the trip immediately after editing trip, it converts the end datetime into UTC, and starts the trip.
     *
     * @param Request $request
     * @return redirect
     */
    public function create(Request $request) {
        Trip::create($request->all());
        ContactTrip::createTrip($request->get('contacts'), Trip::getLastTripId());
        $timezone = User::getTimeZone();
        $datetime = $request->get('end-date') . ' ' . $request->get('end-time');
        $datetimeobj = new DateTime($datetime, $timezone);
        $datetime = $datetimeobj->utc->format("Y-m-d H:i:s");
        Trip::startTrip(Trip::getLastTripId(), $datetime, $request->get('start-date'), $request->get('email'));
        return redirect('/');
    }

    /**
     * Deletes the trip by ID
     *
     * If the user tries to remove a trip he doesn't own, it returns 404
     * 
     * @param Request $request
     * @return redirect
     */
    public function remove(Request $request) {
        $id = $request->query('id');
        if (!Trip::tripIdValid($id, Session::get('id'))) {
            return view('errors.404');
        }
        Trip::remove($id);
        return redirect('/trips');
    }

    /**
     * Starts the trip
     * 
     * It converts the end datetime to utc
     *
     * @param Request $request
     * @return redirect
     */
    public function startTrip(Request $request) {
        $timezone = User::getTimeZone();
        $datetime = $request->get('end-date') . ' ' . $request->get('end-time');
        $datetimeobj = new DateTime($datetime, $timezone);
        $datetime = $datetimeobj->utc->format("Y-m-d H:i:s");
        Trip::startTrip($request->get('id'), $datetime, $request->get('start-date'), $request->get('email'));
        return redirect('/');
    }

    /**
     * Reports that the user is safe
     *
     * @return view|redirect
     */
    public function returnedLate() {
        if (!User::contactsAlerted()) {
            return view('errors.404');
        }

        // TODO: Send the email

        Trip::returned();

        return redirect('/');
    }

    /**
     * Reports that the user has returned on time
     *
     * @param Request $request
     * @return redirect
     */
    public function returned(Request $request) {
        if ($request->get('email')) {
            Trip::returned();
            return redirect('/');
        }
        
        Trip::over();
        return redirect('/');
    }
}
