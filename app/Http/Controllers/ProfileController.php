<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ICanBoogie\DateTime;
use App\Profile;
use App\Contact;
use App\User;
use Session;
use Image;

/**
 * The controller for handling the user's profile
 * 
 * If you try to access any of the view pages without logging in, it will redirect you
 */
class ProfileController extends Controller {
    /**
     * Shows the profile page
     * 
     * @return view
     */
    public function show() {
        $info = Profile::getUserInfo(Session::get('login'));
        $info['contacts'] = json_decode(json_encode(Contact::getContacts(), true));//json_decode converts it to an array
        $datetime = new DateTime($info['birthdate']);
        $info['birthdate'] = $datetime->format('F j, Y');
        return view('profile', $info);
    }

    /**
     * Shows the edit profile page
     *
     * @return view
     */
    public function showEdit() {
        $info = Profile::getUserInfo(Session::get('login'));
        return view('editProfile', $info);
    }

    /**
     * Updates the user's information
     *
     * @param Request $request
     * @return redirect
     */
    public function update(Request $request) {
        $timezone = json_decode(file_get_contents("https://api.askgeo.com/v1/1962/bf9bfd22f43ce3248a7a23d01c9bfa813ab1a3559d133308901b5c3f3c1e4ae/query.json?databases=TimeZone&points=" . $request->get('lat') . "," . $request->get('lon')))->data[0]->TimeZone->TimeZoneId;
        Profile::updateUserInfo($request->all() + ['timezone' => $timezone]);
        Session::put('name',Profile::getUserInfo(Session::get('id'))['firstname']);
        $picture = $request->file('picture');//Gets the file
        if($picture != NULL) {
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            Image::make($picture)->resize(300,300)->save(public_path('/uploads/avatars/' . $filename));
            Profile::changePicture($filename);
        }
        return redirect('./');
    }

    /**
     * Updates the contacts of the user
     *
     * @param Request $request
     * @return redirect
     */
    public function editContact(Request $request) {
        Contact::updateContacts($request->all());
        return redirect('./');
    }

    public function changePicture(Request $request) {
        $picture = $request->file('picture');
        $filename = time() . '.' . $picture->getClientOriginalExtension();
        Image::make($picture)->resize(300,300)->save(public_path('/uploads/avatars/' . $filename));
        Profile::changePicture($filename);
        return redirect('profile/edit');
    }
}