<?php

/**
 * INSTELLINGEN
 */
Route::resource('instellingen', 'Instelling\PersonController', ['only' => ['index', 'update']]);

Route::resource('instellingen/{hulpverlener}/mantelzorgers', 'Instelling\MantelzorgerController');

Route::resource('instellingen/{mantelzorger}/oudere', 'Instelling\OudereController');

/**
 * STATS
 */
Route::get('stats/activity', [
    'uses' => 'Stats\ActivityController@index',
    'as'   => 'stats.activity',
]);

Route::get('stats/insights', [
    'uses' => 'Stats\InsightsController@index',
    'as'   => 'stats.insights',
]);

Route::post('stats/insights/ouderen', [
    'uses' => 'Stats\InsightsController@ouderen',
]);

Route::post('stats/activity/sessions', [
    'uses' => 'Stats\ActivityController@sessions',
]);

Route::post('stats/activity/organisation-sessions', [
    'uses' => 'Stats\ActivityController@organisations'
]);

/**
 * RAPPORT
 */
Route::resource('report', 'RapportController', ['only' => ['index', 'store', 'destroy', 'show']]);

Route::post('report/destroy', 'RapportController@destroyBatch');

/**
 * INSTRUMENT
 */

Route::get('instrument', [
    'uses' => 'InstrumentController@index',
    'as'   => 'instrument'
]);

Route::get('instrument/export', [
    'uses' => 'InstrumentController@export',
    'as'   => 'export'
]);
Route::post('instrument/import', [
    'uses' => 'InstrumentController@import',
    'as'   => 'import'
]);

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

Route::post('instrument/{panel}/{session}', [
    'uses' => 'InstrumentController@postPanel',
    'as'   => 'instrument.panel.submit'
]);

Route::get('instrument/{panel}/{session}', [
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
Route::resource('organisations/{id}/locations', 'Organisation\LocationController', ['only' => ['index', 'store']]);

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

Route::resource('survey', 'Questionnaire\QuestionnaireController', ['only' => ['index', 'store', 'update']]);

Route::resource('survey/{survey}/panel', 'Questionnaire\PanelController', ['only' => ['store', 'update']]);

Route::post('survey/{survey}/panel/sort', 'Questionnaire\PanelController@sort');

Route::resource('panel/{panel}/question', 'Questionnaire\QuestionController', ['only' => ['index', 'store', 'update']]);

Route::resource('question/{question}/choise', 'Questionnaire\ChoiseController', ['only' => ['store', 'update']]);

Route::post('question/{question}/choise/sort', 'Questionnaire\ChoiseController@sort');

/**
 * API ROUTES
 */

Route::group(['prefix' => 'api'], function () {
    Route::get('mantelzorger/{mantelzorger}/ouderen', 'Api\MantelzorgerController@ouderen');
});

/**
 * INDEX
 */
Route::post('', [
    'uses' => 'IndexController@postIndex',
    'as'   => 'beta.post'
]);

Route::get('login', [
    'uses' => 'IndexController@getLogin',
    'as'   => 'login'
]);
Route::post('login', [
    'uses' => 'IndexController@postLogin',
    'as'   => 'login.post'
]);

Route::get('logout', [
    'uses' => 'IndexController@getLogout',
    'as'   => 'logout'
]);

Route::get('reminder', [
    'uses' => 'IndexController@getReminder',
    'as'   => 'reminder'
]);
Route::post('reminder', [
    'uses' => 'IndexController@postReminder',
    'as'   => 'reminder.post'
]);

Route::get('reset/{token}', [
    'uses' => 'IndexController@getReset',
    'as'   => 'reset'
]);
Route::post('reset/{token}', [
    'uses' => 'IndexController@postReset',
    'as'   => 'reset.post'
]);

Route::get('hijack/{user}', [
    'uses' => 'IndexController@getHijack',
    'as'   => 'hijack'
]);
Route::get('rejack', [
    'uses' => 'IndexController@getRejack',
    'as'   => 'rejack'
]);

/**
 * HOME
 */

Route::get('instrument', ['as' => 'dash', 'uses' => 'InstrumentController@index']);
Route::get('', ['as' => 'home', 'uses' => 'IndexController@getIndex']);
