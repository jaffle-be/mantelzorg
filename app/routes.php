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

Route::pattern('hulpverlener', '\d+');
Route::model('hulpverlener', 'User');

Route::pattern('mantelzorger', '\d+');
Route::model('mantelzorger', 'Mantelzorger\Mantelzorger');

/**
 * INSTELLINGEN
 */
Route::resource('instellingen', 'Instelling\PersonController', array('only' => array('index', 'update')));

Route::resource('instellingen/{hulpverlener}/mantelzorgers', 'Instelling\MantelzorgerController');

Route::resource('instellingen/{mantelzorger}/oudere', 'Instelling\OudereController');

/**
 * DASH
 */

Route::controller('dash', 'DashController');

/**
 * RAPPORT
 */
Route::controller('rapport', 'RapportController');

/**
 * INSTRUMENT
 */
Route::controller('instrument','InstrumentController');

/**
 * INSCHRIJVINGEN
 */
Route::resource('inschrijvingen', 'InschrijvingController', array('only' => array('index', 'edit', 'update')));

/**
 * ORGANISATIONS
 */
Route::resource('organisations/{id}/locations', 'Organisation\\LocationController', array('only' => array('index', 'store')));

Route::resource('organisations', 'OrganisationController', array('only' => array('store')));

/**
 * HULPVERLENERS
 */
Route::resource('hulpverleners', 'HulpverlenerController', array('only' => array('index', 'edit', 'update')));


/**
 * INDEX
 */
Route::controller('', 'IndexController');