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

Route::pattern('survey', '\d+');
Route::model('survey', 'Questionnaire\Session');

/**
 * INSTELLINGEN
 */
Route::resource('instellingen', 'Instelling\PersonController', array('only' => array('index', 'update')));

Route::resource('instellingen/{hulpverlener}/mantelzorgers', 'Instelling\MantelzorgerController');

Route::resource('instellingen/{mantelzorger}/oudere', 'Instelling\OudereController');

/**
 * RAPPORT
 */
Route::controller('rapport', 'RapportController');

/**
 * INSTRUMENT
 */

Route::get('instrument', array(
    'uses' => 'InstrumentController@index',
    'as' => 'instrument'
));

Route::post('instrument', array(
    'uses' => 'InstrumentController@postIndex',
    'as' => 'instrument.submit'
));

Route::post('instrument/{panel}/{survey}', array(
    'uses' => 'InstrumentController@postPanel',
    'as' => 'instrument.panel.submit'
));

Route::get('instrument/{panel}/{survey}', array(
    'uses' => 'InstrumentController@getPanel',
    'as' => 'instrument.panel.get'
));

/**
 * INSCHRIJVINGEN
 */
Route::resource('inschrijvingen', 'InschrijvingController', array('only' => array('index', 'edit', 'update')));

/**
 * ORGANISATIONS
 */
Route::resource('organisations/{id}/locations', 'Organisation\LocationController', array('only' => array('index', 'store')));

Route::resource('organisations', 'OrganisationController', array('only' => array('store')));

/**
 * HULPVERLENERS
 */
Route::resource('hulpverleners', 'HulpverlenerController', array('only' => array('index', 'edit', 'update')));

Route::post('hulpverleners/regen-passwords', array('uses' => 'HulpverlenerController@regenPasswords', 'as' => 'hulpverleners.regen-passwords'));

/**
 * QUESTIONAIRES
 */

Route::resource('questionnaires', 'Questionnaire\QuestionnaireController', array('only' => array('index', 'store', 'update')));

Route::resource('questionnaires/{questionnaire}/panels', 'Questionnaire\PanelController', array('only' => array('store', 'update')));

Route::post('questionnaires/{questionnaire}/panels/sort', 'Questionnaire\PanelController@sort');

Route::resource('panels/{panel}/questions', 'Questionnaire\QuestionController', array('only' => array('index', 'store', 'update')));

Route::resource('questions/{question}/choises', 'Questionnaire\ChoiseController', array('only' => array('store', 'update')));

Route::post('questions/{question}/choises/sort', 'Questionnaire\ChoiseController@sort');


/**
 * API ROUTES
 */

Route::group(array('prefix' => 'api'), function()
{
    Route::get('mantelzorger/{mantelzorger}/ouderen', 'Api\MantelzorgerController@ouderen');
});


/**
 * INDEX
 */
Route::controller('', 'IndexController');

/**
 * HOME
 */

Route::get('instrument', array('as' => 'dash', 'uses' => 'InstrumentController@index'));
Route::get('', array('as' => 'home', 'uses' => 'IndexController@getIndex'));
