<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;

/**
 * This class is for handling email complaints, email bounces, and sending emails
 */
class EmailController extends Controller {
    /**
     * Blacklists the bounced recipient
     *
     * Amazon SES sends a post request here when an email bounces
     * 
     * @param Request $request
     * @return void
     */
    public function bounced(Request $request) {
        $json = json_decode($request->getContent());
        $email = json_decode($json->Message)->bounce->bouncedRecipients[0]->emailAddress;
        Notification::addBlackList($email);
    }

    /**
     * Same as bounced()
     *
     * @param Request $request
     * @return void
     */
    public function complaint(Request $request) {
        $json = json_decode($request->getContent());
        $email = json_decode($json->Message)->complaint->complainedRecipients[0]->emailAddress;
        Notification::addBlackList($email);
    }
}
