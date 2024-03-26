<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//TODO: Add a 'resend email' link
//TODO: Add criteria for the password

// Misc

Route::get('/', 'MiscViewsController@portalHome');

Route::get('home', 'MiscViewsController@home');

// Auth

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/login', 'Auth\LoginController@show');

Route::get('/register', 'Auth\RegisterController@show');

Route::post('/login', 'Auth\LoginController@login');

Route::post('/register', 'Auth\RegisterController@register');

Route::post('/emailused', 'Auth\RegisterController@emailUsed');

Route::get('/verification', 'MiscViewsController@verification');

Route::get('/verify', 'Auth\RegisterController@verify');

Route::get('/verified', 'MiscViewsController@verified');

Route::get('/forgotpassword', 'Auth\RegisterController@showForgotPasswordPage');

Route::post('/forgotpassword', 'Auth\RegisterController@forgotPassword');

Route::get('/forgotpassword/emailsent', 'Auth\RegisterController@showForgotPasswordEmailSent');

Route::get('/passwordreset','Auth\RegisterController@resetPasswordForm');

Route::post('/passwordreset','Auth\RegisterController@resetPassword');

// Profile

// Route::get('/profile', 'ProfileController@show')->middleware('login','verification');

Route::get('/profile/edit', 'ProfileController@showEdit')->middleware('login','verification');

Route::post('/profile/edit', 'ProfileController@update');

Route::post('/profile/editcontact', 'ProfileController@editContact');

Route::post('/profile/add_contact', 'ProfileController@addContact');

Route::post('/profile/change_picture', 'ProfileController@changePicture');

// Trips

Route::get('/trips/create_trip', 'TripController@show')->middleware('login','verification');

Route::get('/trips/returnedlate', 'TripController@returnedLate')->middleware('login','verification');

Route::post('/trips/returned', 'TripController@returned');

Route::post('/trips/create_trip', 'TripController@create');

// Email

Route::post('/email_bounced', 'EmailController@bounced');

Route::get('/email_bounced', 'MiscViewsController@fileNotFound');

Route::post('/email_complaint', 'EmailController@complaint');

Route::get('/email_complaint', 'MiscViewsController@fileNotFound');
