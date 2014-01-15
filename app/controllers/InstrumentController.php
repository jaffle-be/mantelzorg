<?php

/**
 * Class InstrumentController
 */
class InstrumentController extends AdminController{


    /**
     * @var Questionnaire\Questionnaire
     */
    protected $questionnaire;

    /**
     * @var User
     */
    protected $hulpverlener;

    /**
     * @var Questionnaire\Session
     */
    protected $session;

    /**
     * @param \Questionnaire\Questionnaire $questionnaire
     */
    public function __construct(\Questionnaire\Questionnaire $questionnaire, User $hulpverlener, \Questionnaire\Session $session)
    {
        $this->questionnaire = $questionnaire;

        $this->hulpverlener = $hulpverlener;

        $this->session = $session;

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

        $surveys = $this->session->where('user_id', $hulpverlener->id)->get();

        if(!$questionnaire)
        {
            return Redirect::route('home');
        }

        $this->layout->content = View::make('instrument.index', compact(array('questionnaire', 'hulpverlener', 'surveys')));
    }

    public function postIndex()
    {
        $input = Input::except('token');

        $hulpverlener = $this->hulpverlener->with(array(
            'mantelzorgers','mantelzorgers.oudere'
        ))->find(Auth::user()->id);

        $mantelzorger = $hulpverlener->mantelzorgers->find($input['mantelzorger']);

        if(!$mantelzorger)
        {
            return Redirect::back();
        }

        $oudere = $mantelzorger->oudere->find($input['oudere']);

        if(!$oudere)
        {
            return Redirect::back();
        }

        $questionnaire = $this->questionnaire->with(array(
            'panels' => function($query){
                    $query->orderBy('panel_weight');
                }
        ))->active()->first();

        $survey = Memorize::newSurvey($mantelzorger, $oudere, $questionnaire);

        return Redirect::route('instrument.panel.get', array($questionnaire->panels->first()->id, $survey->id));
    }

    /**
     * @param $panel
     */
    public function getPanel($panel, $survey)
    {
        $questionnaire = $panel->questionnaire;

        $this->layout->content = View::make('instrument.panel', compact(array('panel', 'questionnaire', 'survey')));
    }

    /**
     * @param $panel
     */
    public function postPanel($panel, $survey)
    {
        //save all input into our session
        $questions = $panel->questions;

        foreach($questions as $question)
        {
            Memorize::question($question, $survey);
        }

        $next = $panel->questionnaire->nextPanel($panel);

        if($next)
        {
            return Redirect::route('instrument.panel.get', array($next->id, $survey->id));
        }
        else
        {
            return Redirect::route('instrument');
        }
    }

}