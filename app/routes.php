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

Route::model('hulpverlener', 'User');

Route::resource('instellingen', 'Instelling\PersonController', array('only' => array('index', 'update')));

Route::resource('instellingen/{hulpverlener}/mantelzorgers/', 'Instelling\MantelzorgerController');

Route::controller('dash', 'DashController');

Route::controller('rapport', 'RapportController');

Route::controller('instrument','InstrumentController');

Route::resource('inschrijvingen', 'InschrijvingController', array('only' => array('index', 'edit', 'update')));

Route::resource('organisations/{id}/locations', 'Organisation\\LocationController', array('only' => array('index', 'store')));

Route::resource('organisations', 'OrganisationController', array('only' => array('store')));

Route::resource('hulpverleners', 'HulpverlenerController', array('only' => array('index', 'edit', 'update')));

Route::controller('', 'IndexController');