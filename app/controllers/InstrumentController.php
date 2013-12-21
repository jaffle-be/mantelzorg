<?php

use Auth;

/**
 * Class InstrumentController
 */
class InstrumentController extends AdminController{


    /**
     * @var Questionnaire\Questionnaire
     */
    protected $questionnaire;

    /**
     * @param \Questionnaire\Questionnaire $questionnaire
     */
    public function __construct(\Questionnaire\Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;

        $this->beforeFilter('auth');
    }

    /**
     *
     */
    public function index()
    {
        $questionnaire = $this->questionnaire->with(array(
            'panels' => function($query){
                    $query->orderBy('panel_weight');
                }
        ))->active()->first();

        $hulpverlener = Auth::user();

        $hulpverlener->load('mantelzorgers');

        if(!$questionnaire)
        {
            return Redirect::route('home');
        }

        $this->layout->content = View::make('instrument.index', compact(array('questionnaire', 'hulpverlener')));
    }

    public function postIndex()
    {
        $questionnaire = $this->questionnaire->with(array(
            'panels' => function($query){
                    $query->orderBy('panel_weight');
                }
        ));

        return Redirect::route('instrument.panel', $questionnaire->panels->first()->id);
    }

    /**
     * @param $panel
     */
    public function getPanel($panel)
    {
        var_dump($panel);
    }

    /**
     * @param $panel
     */
    public function postPanel($panel)
    {

    }

}