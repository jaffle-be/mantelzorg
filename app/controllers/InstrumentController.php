<?php
use Barryvdh\Snappy\PdfWrapper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Mantelzorger\Mantelzorger;
use Mantelzorger\Oudere;
use Questionnaire\Answer;
use Questionnaire\Choise;
use Session as SessionStore;
use Questionnaire\Session;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        $bool['must'] = [
            ['term' => ['user_id' => $hulpverlener->id]]
        ];

        if (Input::get('query')) {
            $bool['should'] = [
                ['nested' => [
                    'path'  => 'mantelzorger',
                    'query' => [
                        'match' => ['mantelzorger.identifier.raw' => Input::get('query')]
                    ]
                ]],
                ['nested' => [
                    'path'  => 'oudere',
                    'query' => [
                        'match' => ['oudere.identifier.raw' => Input::get('query')]
                    ]
                ]]
            ];
        }

        $surveys = $search
            ->with(array('questionnaire', 'questionnaire.questions', 'answers', 'answers.choises'))
            ->filterBool($bool)
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
            'questions'         => function ($query) {
                $query->orderBy('sort');
            },
            'questions.choises' => function ($query) {
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

    public function export()
    {
        $ids = Input::get('ids');

        if (empty($ids)) {
            return Redirect::back();
        }

        //this will export into an array of json objects.
        //which can then be used for recovery
        $sessions = $this->session->whereIn('id', $ids)->get();

        $sessions->load([
            'user',
            'mantelzorger',
            'oudere',
            'answers',
            'answers.choises',
        ]);

        if ($hijacked = SessionStore::get('hijack-original')) {
            $userid = $hijacked;
        } else {
            dd('test2');
            $userid = Auth::user()->id;
        }

        $directory = storage_path('instruments');

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755);
        }

        $filename = Carbon::now()->timestamp . '-' . $userid . '.json';

        $path = $directory . '/' . $filename;

        File::put($path, $sessions->toJson());

        return Response::download($path);
    }

    public function import()
    {
        $transaction = DB::beginTransaction();

        try {
            /** @var UploadedFile $file */

            $file = Input::file('import');

            $directory = storage_path('instruments/import');

            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory);
            }

            $file->move($directory, $file->getClientOriginalName());

            $surveys = json_decode(File::get($directory . '/' . $file->getClientOriginalName()));

            foreach ($surveys as $survey) {
                //check if all users, mantelzorgers and ouderen exist.
                $user = $this->getImportingUser($survey);

                $mantelzorger = $this->getImportingMantelzorger($survey);

                $oudere = $this->getImportingOudere($survey);

                //all persons have been found.
                //intrument is the same
                //overwrite any user related data, just in case.
                $survey->user_id = $user->id;
                $survey->user->id = $user->id;
                $survey->mantelzorger_id = $mantelzorger->id;
                $survey->mantelzorger->id = $mantelzorger->id;
                $survey->oudere_id = $oudere->id;
                $survey->oudere->id = $oudere->id;

                $this->importInsert($survey);
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        return Redirect::back();
    }

    protected function getImportingUser($survey)
    {
        $userid = $survey->user_id;

        $user = User::find($userid);

        if ($user && $user->email == $survey->user->email) {
            return $user;
        }

        throw new Exception('User not found');
    }

    protected function getImportingMantelzorger($survey)
    {
        $mantelzorgerid = $survey->mantelzorger_id;

        $mantelzorger = Mantelzorger::find($mantelzorgerid);

        if (!$survey->mantelzorger->identifier) {
            throw new Exception('need identifier for mantelzorger');
        }

        if ($mantelzorger && $mantelzorger->identifier == $survey->mantelzorger->identifier) {
            return $mantelzorger;
        }

        throw new Exception('Mantelzorger nog found');
    }

    protected function getImportingOudere($survey)
    {
        $oudereid = $survey->oudere_id;

        $oudere = Oudere::find($oudereid);

        if (!$survey->oudere->identifier) {
            throw new Exception('need identifier for oudere');
        }

        if ($oudere && $oudere->identifier == $survey->oudere->identifier) {
            return $oudere;
        }

        throw new Exception('Oudere not found');
    }

    protected function importInsert($survey)
    {
        //user info exists.
        //now we need to create a session, with NEW ids
        //and set all session ids to the newly created one.
        //unguard model, so we can also set the created_at and updated_at correctly

        Model::unguard();

        $created_survey = Session::create([
            'user_id'          => $survey->user_id,
            'mantelzorger_id'  => $survey->mantelzorger_id,
            'oudere_id'        => $survey->oudere_id,
            'questionnaire_id' => $survey->questionnaire_id,
            'created_at'       => $survey->created_at,
            'updated_at'       => $survey->updated_at
        ]);

        $id = $created_survey->id;

        foreach ($survey->answers as $answer) {

            $created_answer = Answer::create([
                "session_id"  => $id,
                "question_id" => $answer->question_id,
                "explanation" => $answer->explanation,
                "created_at"  => $answer->created_at,
                "updated_at"  => $answer->updated_at,
            ]);

            foreach ($answer->choises as $choise) {
                $known_choise = Choise::find($choise->id);

                if (!$known_choise || $known_choise->title != $choise->title) {
                    throw new Exception('choise not known');
                }

                $created_answer->choises()->attach($choise->id, [
                    'created_at' => $choise->pivot->created_at,
                    'updated_at' => $choise->pivot->updated_at
                ]);
            }
        }

        Model::reguard();
    }
}