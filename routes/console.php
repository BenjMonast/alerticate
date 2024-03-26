<?php

use Illuminate\Foundation\Inspiring;
use App\Notification;
use App\User;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('email', function() {
    Notification::sendEmail(['tester2@getnada.com'], 'Hello', 'I sent this using php');
});

Artisan::command('SMS', function() {
    Notification::sendSMS(['4087670958'], 'Hi');
});

Artisan::command('test', function() {
    if (User::isVerified(1)) {
        echo 'True';
    } else {
        echo 'False';
    }
});