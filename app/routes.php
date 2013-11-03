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

Route::controller('instellingen', 'InstellingenController');

Route::controller('dash', 'DashController');

Route::controller('rapport', 'RapportController');

Route::controller('instrument','InstrumentController');

Route::controller('', 'IndexController');