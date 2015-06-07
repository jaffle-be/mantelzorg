<?php
use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\Snappy\PdfWrapper;

/**
 * Class InstrumentController
 */
class InstrumentController extends AdminController
{

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

    public function index()
    {
        $questionnaire = $this->questionnaire->with(array(
            'panels' => function ($query) {
                $query->orderBy('panel_weight');
            }
        ))->active()->first();

        $hulpverlener = Auth::user();
        
        $hulpverlener->load('mantelzorgers');

        $search = $this->session->search();

        $surveys = $search
            ->with(array('questionnaire', 'questionnaire.questions', 'answers', 'answers.choises'))
            ->filterTerm('user_id', $hulpverlener->id)
            ->filterMulti_match(['mantelzorger.firstname', 'mantelzorger.lastname', 'mantelzorger.identifier', 'oudere.firstname', 'oudere.lastname', 'oudere.identifier'], Input::get('query'))
            ->orderBy('mantelzorger.identifier.raw', 'asc')
            ->paginate(1000)
            ->get();

        if (!$questionnaire) {
            return Redirect::route('home');
        }

        $this->layout->content = View::make('instrument.index', compact(array('questionnaire', 'hulpverlener', 'surveys')));
    }

    public function download($id)
    {
        $session = $this->session->with([
            'questionnaire',
            'user',
            'answers',
            'answers.choises',
            'mantelzorger',
            'oudere',
            'oudere.woonSituatie',
            'oudere.oorzaakHulpbehoefte',
            'oudere.mantelzorgerRelation',
            'oudere.belProfiel',
        ])->find($id);

        /** @var PdfWrapper $snappy */
        $snappy = App::make('snappy.pdf.wrapper');

        return $snappy->loadView('instrument.pdf', ['session' => $session])
            ->download($session->questionnaire->title . '.pdf');
    }

    public function newSurvey()
    {
        $input = Input::except('token');

        $hulpverlener = $this->hulpverlener->with(array(
            'mantelzorgers', 'mantelzorgers.oudere'
        ))->find(Auth::user()->id);

        $mantelzorger = $hulpverlener->mantelzorgers->find($input['mantelzorger']);

        if (!$mantelzorger) {
            return Redirect::back();
        }

        $oudere = $mantelzorger->oudere->find($input['oudere']);

        if (!$oudere) {
            return Redirect::back();
        }

        $questionnaire = $this->questionnaire->with(array(
            'panels' => function ($query) {
                $query->orderBy('panel_weight');
            }
        ))->active()->first();

        $survey = Memorize::newSurvey($mantelzorger, $oudere, $questionnaire);

        return Redirect::route('instrument.panel.get', array($questionnaire->panels->first()->id, $survey->id));
    }

    public function destroy()
    {
        $ids = Input::get('ids');

        if (count($ids)) {
            $hulpverlener = Auth::user();

            $surveys = $this->session->where('user_id', $hulpverlener->id)->whereIn('id', $ids)->with([
                'answers'
            ])->get();

            foreach ($surveys as $survey) {
                $survey->delete();
            }
        }

        return [];
    }

    /**
     * @param $panel
     */
    public function getPanel($panel, $survey)
    {
        $panel->load([
            'questionnaire',
            'questionnaire.panels',
            'questions' => function ($query) {
                $query->orderBy('sort');
            },
            'questions.choises' => function($query){
                $query->orderBy('sort_weight');
            }
        ]);

        $survey->load(array('answers', 'answers.choises'));

        $questionnaire = $panel->questionnaire;

        $arguments = array_merge([
            'zeroPadding' => true,
        ], compact(array('panel', 'questionnaire', 'survey')));

        $this->layout->content = View::make('instrument.panel', $arguments);
    }

    /**
     * @param $panel
     */
    public function postPanel($panel, $survey)
    {
        //save all input into our session
        $questions = $panel->questions;

        foreach ($questions as $question) {
            Memorize::question($question, $survey);
        }

        if ($next = Input::get('next_panel')) {
            return Redirect::route('instrument.panel.get', array($next, $survey->id));
        }

        $next = $panel->questionnaire->nextPanel($panel);

        if ($next) {
            return Redirect::route('instrument.panel.get', array($next->id, $survey->id));
        } else {
            return Redirect::route('instrument');
        }
    }
}