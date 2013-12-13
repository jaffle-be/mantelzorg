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

Route::pattern('questionnaire', '\d+');
Route::model('questionnaire', 'Questionnaire\Questionnaire');

Route::pattern('panel', '\d+');
Route::model('panel', 'Questionnaire\Panel');

Route::pattern('question', '\d+');
Route::model('question', 'Questionnaire\Question');

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
 * QUESTIONAIRES
 */

Route::resource('questionnaires', 'Questionnaire\QuestionnaireController', array('only' => array('index', 'store', 'update')));

Route::resource('questionnaires/{questionnaire}/panels', 'Questionnaire\PanelController', array('only' => array('store', 'update')));

Route::post('questionnaires/{questionnaire}/panels/sort', 'Questionnaire\PanelController@sort');

Route::resource('panels/{panel}/questions', 'Questionnaire\QuestionController', array('only' => array('index', 'store')));

Route::resource('questions/{question}/choises', 'Questionnaire\ChoiseController', array('only' => array('store')));


/**
 * INDEX
 */
Route::controller('', 'IndexController');