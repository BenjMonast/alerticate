<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PHPMailer;
use Session;
use ICanBoogie\DateTime;
use Twilio\Rest\Client;
// include '\vendor\phpclasses\verify-email\class.verifyEmail.php';

/**
 * This class is for adding emails to the blacklist and sending emails
 */
class Notification extends Model {
    /**
     * Adds an email to the blacklist
     *
     * @param string $content
     * @return void
     */
    public static function addBlackList($content) {
        try {
            DB::table('blacklist')->insert([
                'email' => $content
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::table('blacklist')->insert([
                'email' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tells you if the email is blacklisted
     *
     * @param string $email
     * @return bool
     */
    public static function blackListed($email) {
        $query = DB::table('blacklist')->select('*')->where('email', $email)->get();
        return (count($query) ? true : false);
    }

    /**
     * Sends an email
     *
     * @return void
     */
    public static function sendEmail($recipients, $subject, $body) {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->setFrom('alerticate@gmail.com', 'Alerticate');
        foreach ($recipients as $r) {
            $domain = explode('@', $r)[1];
            if (!Notification::blackListed($r) and checkdnsrr($domain)) {
                $mail->addAddress($r);
            }
        }
        $mail->Host = 'email-smtp.us-west-2.amazonaws.com';
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        if (!$mail->send()) {
            echo "Email not sent. " , $mail->ErrorInfo , PHP_EOL;
        }
    }

    public static function sendSMS($recepients, $body) {
        // $params = [
        //     'credentials' => [
        //         'key' => env('SNS_IAM_KEY'),
        //         'secret' => env('SNS_IAM_SECRET')
        //     ],
        //     'region' => 'us-west-2',
        //     'version' => 'latest'
        // ];
        // $sns = new \Aws\Sns\SnsClient($params);

        // foreach ($recepients as $r) {
        //     $args = [
        //         'SenderID' => 'FindMe',
        //         'SMSType' => 'Transactional',
        //         'Message' => $body,
        //         'PhoneNumber' => '+1' . $r
        //     ];
    
        //     echo $sns->publish($args);
        // }

        foreach ($recepients as $r) {
            $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH'));
            $client->messages->create(
                '+1' . $r,
                [
                    'from' => '+13854693711',
                    'body' => $body
                ]
            );
        }
    }
    
    /**
     * Sends the verification email
     * 
     * @param string $email
     * @param string $hash
     * @return void
     */
    public static function sendVerificationEmail($email = null, $hash = null) {
        $id = User::getId($email);
        $body = 'Link: ' . url('verify') . '?id=' . $id . '&hash=' . $hash;
        Notification::sendEmail([$email],'Click the link to activate your account',$body);
    }

    /**
     * Ties together sending emails and sending text messages
     *
     * @param array $email_recepients
     * @param array $sms_recepients
     * @param string $subject
     * @param string $email_body
     * @param string $sms_body
     * @return void
     */
    public static function notify($trip_id, $subject, $email_body, $sms_body) {
        $contacts = ContactTrip::getSelectedContacts($trip_id);
        $email_recipients = array_filter($contacts, function($x) {return $x['email'] != NULL;});
        $email_recipients = array_map(function($x) {return $x['email'];}, $email_recipients);
        $sms_recipients = array_filter($contacts, function($x) {return $x['phone'] != NULL;});
        $sms_recipients = array_map(function($x) {return $x['phone'];}, $sms_recipients);
        Notification::sendEmail($email_recipients, $subject, $email_body);
        Notification::sendSMS($sms_recipients, $sms_body);
    }

    public static function checkEveryMinute() {
        $now = new DateTime('now', 'Etc/UCT');
        $trips = DB::table('trips')
            ->where('active', '1')
            ->select('*')
            ->get();
        foreach ($trips as $k => $v) {
            $datetime = new DateTime($v->end_time, 'Etc/UCT');
            if ($now > $datetime) {
                    $name = Profile::getUserInfo($v->user_id)['firstname'];

                    // date_default_timezone_set(User::getTimezone($v->user_id));
                    // if ($v->start_date != NULL) {
                    //     $dt = new DateTime($v->start_date, 'Etc/UCT');
                    //     $v->start_date = $dt->format("F j, Y");
                    // }
                    // $dt = new DateTime($v->end_time, 'Etc/UCT');
                    // $v->end_time = $dt->local->format("F j, Y") . " at " . $dt->local->format("g:i A");

                    // $v->destination_lat = floatval($v->destination_lat);
                    // $v->destination_lon = floatval($v->destination_lon);

                    $subject = $name . ' has not returned from his trip';
                    $email_body = "If you recieved this email, it means that $name has not returned from his trip. Keep in mind that this could be a mistake because $name forgot to say that he returned.";
                    
                    // Here is information about $name's trip:" . 
                    // ($v->start_date != NULL ? "\nThe trip started $v->start_date." : '' ) . "
                    // The trip was supposed to end $v->end_time.
                    // The destination was $v->destination_address ($v->destination_lat, $v->destination_lon).";
                    $sms_body = "If you recieved this email, it means that $name has not returned from his trip. Keep in mind that this could be a mistake because $name forgot to say that he returned.";

                    Notification::notify($v->trip_id, $subject, $email_body, $sms_body);

                    DB::table('trips')
                        ->where('trip_id', $v->trip_id)
                        ->update([
                            'active' => '2'
                        ]);
            }
        }
    }
}