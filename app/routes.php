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
Route::model('hulpverlener', 'App\User');

Route::pattern('mantelzorger', '\d+');
Route::model('mantelzorger', 'App\Mantelzorger\Mantelzorger');

Route::pattern('questionnaire', '\d+');
Route::model('questionnaire', 'App\Questionnaire\Questionnaire');

Route::pattern('panel', '\d+');
Route::model('panel', 'App\Questionnaire\Panel');

Route::pattern('question', '\d+');
Route::model('question', 'App\Questionnaire\Question');

Route::pattern('survey', '\d+');
Route::model('survey', 'App\Questionnaire\Session');

/**
 * INSTELLINGEN
 */
Route::resource('instellingen', 'Instelling\PersonController', ['only' => ['index', 'update']]);

Route::resource('instellingen/{hulpverlener}/mantelzorgers', 'Instelling\MantelzorgerController');

Route::resource('instellingen/{mantelzorger}/oudere', 'Instelling\OudereController');

/**
 * STATS
 */
Route::get('stats', [
    'uses' => 'StatsController@index',
    'as' => 'stats.index',
]);

Route::post('stats/ouderen', [
    'uses' => 'StatsController@ouderen',
]);

Route::post('stats/sessions', [
    'uses' => 'StatsController@sessions',
]);

/**
 * RAPPORT
 */
Route::get('rapport', [
    'uses' => 'RapportController@index',
    'as'   => 'rapport.index'
]);

Route::post('rapport', [
    'uses' => 'RapportController@generate',
    'as'   => 'rapport.generate'
]);

Route::get('rapport/download/{filename}', [
    'uses' => 'RapportController@download',
    'as'   => 'rapport.download'
]);

Route::get('rapport/delete/{filename}', [
    'uses' => 'RapportController@delete',
    'as'   => 'rapport.delete',
]);

/**
 * INSTRUMENT
 */

Route::get('instrument', [
    'uses' => 'InstrumentController@index',
    'as'   => 'instrument'
]);

Route::get('instrument/export', 'InstrumentController@export');
Route::post('instrument/import', 'InstrumentController@import');

Route::get('instrument/download/{id}', [
    'uses' => 'InstrumentController@download',
    'as'   => 'instrument.download',
]);

Route::post('instrument', [
    'uses' => 'InstrumentController@newSurvey',
    'as'   => 'instrument.submit'
]);

Route::post('instrument/destroy', [
    'uses' => 'InstrumentController@destroy',
    'as'   => 'instrument.destroy'
]);

Route::post('instrument/{panel}/{survey}', [
    'uses' => 'InstrumentController@postPanel',
    'as'   => 'instrument.panel.submit'
]);

Route::get('instrument/{panel}/{survey}', [
    'uses' => 'InstrumentController@getPanel',
    'as'   => 'instrument.panel.get'
]);

/**
 * INSCHRIJVINGEN
 */
Route::resource('inschrijvingen', 'InschrijvingController', ['only' => ['index', 'edit', 'update']]);

Route::post('inschrijvingen/destroy', [
    'uses' => 'InschrijvingController@destroy',
    'as'   => 'inschrijvingen.destroy',
]);

/**
 * ORGANISATIONS
 */
Route::resource('organisations/{id}/locations', 'App\Organisation\LocationController', ['only' => ['index', 'store']]);

Route::resource('organisations', 'OrganisationController', ['only' => ['store']]);

/**
 * HULPVERLENERS
 */
Route::resource('hulpverleners', 'HulpverlenerController', ['only' => ['index', 'edit', 'update']]);

Route::post('hulpverleners/regen-passwords', ['uses' => 'HulpverlenerController@regenPasswords', 'as' => 'hulpverleners.regen-passwords']);

Route::post('hulpverleners/destroy', ['uses' => 'HulpverlenerController@destroy', 'as' => 'hulpverleners.destroy']);

/**
 * QUESTIONAIRES
 */

Route::resource('questionnaires', 'App\Questionnaire\QuestionnaireController', ['only' => ['index', 'store', 'update']]);

Route::resource('questionnaires/{questionnaire}/panels', 'App\Questionnaire\PanelController', ['only' => ['store', 'update']]);

Route::post('questionnaires/{questionnaire}/panels/sort', 'App\Questionnaire\PanelController@sort');

Route::resource('panels/{panel}/questions', 'App\Questionnaire\QuestionController', ['only' => ['index', 'store', 'update']]);

Route::resource('questions/{question}/choises', 'App\Questionnaire\ChoiseController', ['only' => ['store', 'update']]);

Route::post('questions/{question}/choises/sort', 'App\Questionnaire\ChoiseController@sort');

/**
 * API ROUTES
 */

Route::group(['prefix' => 'api'], function () {
    Route::get('mantelzorger/{mantelzorger}/ouderen', 'Api\MantelzorgerController@ouderen');
});

/**
 * INDEX
 */
Route::controller('', 'IndexController');

/**
 * HOME
 */

Route::get('instrument', ['as' => 'dash', 'uses' => 'InstrumentController@index']);
Route::get('', ['as' => 'home', 'uses' => 'IndexController@getIndex']);
