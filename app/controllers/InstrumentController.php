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
     * @param \Questionnaire\Questionnaire $questionnaire
     */
    public function __construct(\Questionnaire\Questionnaire $questionnaire, User $hulpverlener)
    {
        $this->questionnaire = $questionnaire;

        $this->hulpverlener = $hulpverlener;

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

        Memorize::set(array(
            'mantelzorger' => $mantelzorger,
            'oudere' => $oudere
        ));


        $questionnaire = $this->questionnaire->with(array(
            'panels' => function($query){
                    $query->orderBy('panel_weight');
                }
        ))->active()->first();

        return Redirect::route('instrument.panel.get', $questionnaire->panels->first()->id);
    }

    /**
     * @param $panel
     */
    public function getPanel($panel)
    {
        $questionnaire = $panel->questionnaire;

        $this->layout->content = View::make('instrument.panel', compact(array('panel', 'questionnaire')));
    }

    /**
     * @param $panel
     */
    public function postPanel($panel)
    {

    }

}