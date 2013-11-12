<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::pattern('id', '\d+');

Route::controller('instellingen', 'InstellingController');

Route::controller('dash', 'DashController');

Route::controller('rapport', 'RapportController');

Route::controller('instrument','InstrumentController');

Route::resource('inschrijvingen', 'InschrijvingController');

Route::resource('organisations/{id}/locations', 'Organisation\\LocationController');

Route::resource('organisations', 'OrganisationController');

//Route::resource('locations', 'LocationController');

Route::resource('hulpverleners', 'HulpverlenerController');

Route::controller('', 'IndexController');