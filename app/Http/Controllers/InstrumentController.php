<?php

namespace App\Http\Controllers;

use App;
use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;
use App\Meta\Context;
use App\Meta\Value;
use App\Questionnaire\Answer;
use App\Questionnaire\Choise;
use App\Questionnaire\Questionnaire;
use App\Questionnaire\Session;
use App\Search\SearchServiceInterface;
use App\User;
use Auth;
use Barryvdh\Snappy\PdfWrapper;
use Carbon\Carbon;
use Chumper\Zipper\Zipper;
use DB;
use Exception;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Input;
use Memorize;
use Redirect;
use Response;
use Session as SessionStore;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class InstrumentController.
 */
class InstrumentController extends AdminController
{
    /**
     * @var \App\Questionnaire\Questionnaire
     */
    protected $questionnaire;

    /**
     * @var User
     */
    protected $hulpverlener;

    /**
     * @var \App\Questionnaire\Session
     */
    protected $session;

    /**
     * @param \App\Questionnaire\Questionnaire $questionnaire
     */
    public function __construct(\App\Questionnaire\Questionnaire $questionnaire, User $hulpverlener, \App\Questionnaire\Session $session)
    {
        $this->questionnaire = $questionnaire;

        $this->hulpverlener = $hulpverlener;

        $this->session = $session;

        $this->middleware('auth');
    }

    public function index(SearchServiceInterface $search)
    {
        $questionnaire = $this->questionnaire->with(['panels'])->active()->first();

        $hulpverlener = Auth::user();

        $hulpverlener->load('mantelzorgers');

        $surveys = $search->search('surveys', $this->searchQuery($hulpverlener));

        $surveys->addQuery('query', Input::get('query'));

        if (!$questionnaire) {
            return Redirect::route('home');
        }

        return view('instrument.index', compact('questionnaire', 'hulpverlener', 'surveys'));
    }

    protected function searchQuery(User $hulpverlener)
    {
        $input = Input::get('query');

        $query = [
            'index' => config('search.index'),
            'type' => 'surveys',
            'body' => [
                'query' => [
                    'filtered' => [
                        'query' => [
                            'bool' => [
                                'should' => [
                                    [
                                        'nested' => [
                                            'path' => 'mantelzorger',
                                            'query' => [
                                                'multi_match' => [
                                                    'query' => $input,
                                                    'fields' => ['mantelzorger.identifier', 'mantelzorger.firstname', 'mantelzorger.lastname'],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'nested' => [
                                            'path' => 'oudere',
                                            'query' => [
                                                'multi_match' => [
                                                    'query' => $input,
                                                    'fields' => ['oudere.identifier', 'oudere.firstname', 'oudere.lastname'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'filter' => [
                            'bool' => [
                                'must' => [
                                    [
                                        'term' => [
                                            'user_id' => $hulpverlener->id,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'sort' => [
                    ['mantelzorger.identifier' => 'asc'],
                ],
            ],
        ];

        if (empty($input)) {
            $query['body']['query']['filtered']['query'] = ['match_all' => []];
        }

        return $query;
    }

    public function view($id)
    {
        $session = $this->sessionDetail($id);

        return view('instrument.view', ['session' => $session]);
    }

    public function download($id)
    {
        $session = $this->sessionDetail($id);

        $now = $this->pdfTimestamp();

        $name = $this->pdfName($session, $now);

        $document = $this->pdfDocument($session);

        return $document->download($name);
    }

    public function batchDownload(Request $request, Zipper $zipper)
    {
        $survey = Questionnaire::active()->first();

        $ids = $request->get('ids');

        $sessions = $this->sessionDetails($ids);

        $time = new Carbon();
        $time = $time->timestamp;
        $files = [];
        $now = $this->pdfTimestamp();

        foreach ($sessions as $session) {
            $name = $this->pdfName($session, $now);

            $document = $this->pdfDocument($session);

            $path = storage_path("app/batch-pdf/$time/$name");

            $document->save($path);

            $files[] = $path;
        }

        $zip = storage_path("app/batch-pdf/$time/{$survey->title}-$now.zip");

        $zipper->make($zip)->add($files)->close();

        $filesystem = app('files');

        foreach ($files as $file) {
            $filesystem->delete($file);
        }

        return response()->download($zip);
    }

    public function newSurvey()
    {
        $input = Input::except('token');

        $hulpverlener = $this->hulpverlener->with(array(
            'mantelzorgers', 'mantelzorgers.oudere',
        ))->find(Auth::user()->id);

        $mantelzorger = $hulpverlener->mantelzorgers->find($input['mantelzorger']);

        if (!$mantelzorger) {
            return Redirect::back();
        }

        $oudere = $mantelzorger->oudere->find($input['oudere']);

        if (!$oudere) {
            return Redirect::back();
        }

        $questionnaire = $this->questionnaire->with(['panels'])->active()->first();

        $survey = Memorize::newSurvey($mantelzorger, $oudere, $questionnaire);

        return Redirect::route('instrument.panel.get', array($questionnaire->panels->first()->id, $survey->id));
    }

    public function destroy()
    {
        $ids = Input::get('ids');

        if (count($ids)) {
            $hulpverlener = Auth::user();

            $surveys = $this->session->where('user_id', $hulpverlener->id)->whereIn('id', $ids)->with([
                'answers',
            ])->get();

            foreach ($surveys as $survey) {
                $survey->delete();
            }
        }

        return [];
    }

    /**
     * @param $panel
     *
     * @return \Illuminate\View\View
     */
    public function getPanel($panel, $survey)
    {
        $panel->load([
            'questionnaire',
            'questionnaire.panels',
            'questions',
            'questions.choises',
        ]);

        $survey->load(array('answers', 'answers.choises'));

        $questionnaire = $panel->questionnaire;

        $arguments = array_merge([
            'zeroPadding' => true,
        ], compact('panel', 'questionnaire', 'survey'));

        return view('instrument.panel', $arguments);
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
            return Redirect::route('dash');
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
            'oudere.mantelzorgerRelation',
            'oudere.woonSituatie',
            'oudere.oorzaakHulpbehoefte',
            'oudere.belProfiel',
            'answers',
            'answers.choises',
        ]);

        if ($hijacked = SessionStore::get('hijack-original')) {
            $userid = $hijacked;
        } else {
            $userid = Auth::user()->id;
        }

        $directory = storage_path('instruments');

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755);
        }

        $filename = Carbon::now()->timestamp.'-'.$userid.'.json';

        $path = $directory.'/'.$filename;

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
                File::makeDirectory($directory, 0775, true);
            }

            $file->move($directory, $file->getClientOriginalName());

            $surveys = json_decode(File::get($directory.'/'.$file->getClientOriginalName()));

            foreach ($surveys as $survey) {
                //check if all users, mantelzorgers and ouderen exist.
                $user = $this->setImportingUser($survey);

                $mantelzorger = $this->setImportingMantelzorger($survey);

                $oudere = $this->setImportingOudere($survey);

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
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        return Redirect::back();
    }

    protected function setImportingUser($survey)
    {
        $userid = $survey->user_id;

        $user = User::find($userid);

        $survey->user_id = $user->id;
        $survey->user->id = $user->id;

        if ($user && $user->email == $survey->user->email) {
            return $user;
        }

        throw new Exception('User not found');
    }

    protected function setImportingMantelzorger($survey)
    {
        $mantelzorgerid = $survey->mantelzorger_id;

        $mantelzorger = Mantelzorger::find($mantelzorgerid);

        if (!$survey->mantelzorger->identifier) {
            throw new Exception('need identifier for mantelzorger');
        }

        if ($mantelzorger && $mantelzorger->identifier == $survey->mantelzorger->identifier) {
            return $mantelzorger;
        }

        $mantelzorger = Mantelzorger::create([
            'identifier' => $survey->mantelzorger->identifier,
            'email' => $survey->mantelzorger->email,
            'firstname' => $survey->mantelzorger->firstname,
            'lastname' => $survey->mantelzorger->lastname,
            'male' => $survey->mantelzorger->male,
            'street' => $survey->mantelzorger->street,
            'postal' => $survey->mantelzorger->postal,
            'city' => $survey->mantelzorger->city,
            'phone' => $survey->mantelzorger->phone,
            'birthday' => $this->getBirthday($survey->mantelzorger->birthday),
            'hulpverlener_id' => $survey->user_id,
            'created_at' => $survey->mantelzorger->created_at,
            'updated_at' => $survey->mantelzorger->updated_at,
        ]);

        $survey->mantelzorger_id = $mantelzorger->id;
        $survey->mantelzorger->id = $mantelzorger->id;

        return $mantelzorger;
    }

    protected function setImportingOudere($survey)
    {
        $oudereid = $survey->oudere_id;

        $oudere = Oudere::find($oudereid);

        if (!$survey->oudere->identifier) {
            throw new Exception('need identifier for oudere');
        }

        if ($oudere && $oudere->identifier == $survey->oudere->identifier) {
            return $oudere;
        }

        list($relation, $woonsituatie, $hulpbehoefte, $profiel) = $this->getMetas($survey);

        $oudere = Oudere::create([
            'identifier' => $survey->oudere->identifier,
            'email' => $survey->oudere->email,
            'firstname' => $survey->oudere->firstname,
            'lastname' => $survey->oudere->lastname,
            'male' => $survey->oudere->male,
            'street' => $survey->oudere->street,
            'postal' => $survey->oudere->postal,
            'city' => $survey->oudere->city,
            'phone' => $survey->oudere->phone,
            'birthday' => $this->getBirthday($survey->oudere->birthday),
            'diagnose' => $survey->oudere->diagnose,
            'mantelzorger_id' => $survey->mantelzorger_id,
            'mantelzorger_relation_id' => $relation ? $relation->id : null,
            'created_at' => $survey->oudere->created_at,
            'updated_at' => $survey->oudere->updated_at,
            'woonsituatie_id' => $woonsituatie ? $woonsituatie->id : null,
            'oorzaak_hulpbehoefte_id' => $hulpbehoefte ? $hulpbehoefte->id : null,
            'bel_profiel_id' => $profiel ? $profiel->id : null,
            'details_diagnose' => $survey->oudere->details_diagnose,
        ]);

        return $oudere;
    }

    protected function importInsert($survey)
    {
        //user info exists.
        //now we need to create a session, with NEW ids
        //and set all session ids to the newly created one.
        //unguard model, so we can also set the created_at and updated_at correctly

        Model::unguard();

        $created_survey = Session::create([
            'user_id' => $survey->user_id,
            'mantelzorger_id' => $survey->mantelzorger_id,
            'oudere_id' => $survey->oudere_id,
            'questionnaire_id' => $survey->questionnaire_id,
            'created_at' => $survey->created_at,
            'updated_at' => $survey->updated_at,
        ]);

        $id = $created_survey->id;

        foreach ($survey->answers as $answer) {
            $created_answer = Answer::create([
                'session_id' => $id,
                'question_id' => $answer->question_id,
                'explanation' => $answer->explanation,
                'created_at' => $answer->created_at,
                'updated_at' => $answer->updated_at,
            ]);

            foreach ($answer->choises as $choise) {
                $known_choise = Choise::find($choise->id);

                if (!$known_choise || $known_choise->title != $choise->title) {
                    throw new Exception('choise not known');
                }

                $created_answer->choises()->attach($choise->id, [
                    'created_at' => $choise->pivot->created_at,
                    'updated_at' => $choise->pivot->updated_at,
                ]);
            }
        }

        Model::reguard();
    }

    protected function getBirthday($birthday)
    {
        $birthday = str_replace(' 00:00:00', '', $birthday);

        $birthday = Carbon::createFromFormat('Y-m-d', $birthday)->format('d/m/Y');

        return $birthday;
    }

    protected function getMetas($survey)
    {
        if ($survey->oudere->mantelzorger_relation_id) {
            $relation = $this->getMeta(Context::MANTELZORGER_RELATION, $survey->oudere->mantelzorger_relation_id, $survey->oudere->mantelzorger_relation->value);
        } else {
            $relation = null;
        }

        if ($survey->oudere->woonsituatie_id) {
            $woonsituatie = $this->getMeta(Context::OUDEREN_WOONSITUATIE, $survey->oudere->woonsituatie_id, $survey->oudere->woon_situatie->value);
        } else {
            $woonsituatie = null;
        }

        if ($survey->oudere->oorzaak_hulpbehoefte_id) {
            $hulpbehoefte = $this->getMeta(Context::OORZAAK_HULPBEHOEFTE, $survey->oudere->oorzaak_hulpbehoefte_id, $survey->oudere->oorzaak_hulpbehoefte->value);
        } else {
            $hulpbehoefte = null;
        }

        if ($survey->oudere->bel_profiel_id) {
            $profiel = $this->getMeta(Context::BEL_PROFIEL, $survey->oudere->bel_profiel_id, $survey->oudere->bel_profiel->value);
        } else {
            $profiel = null;
        }

        return array($relation, $woonsituatie, $hulpbehoefte, $profiel);
    }

    /**
     * @param $context
     * @param $survey
     * @param $actual
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model|static
     */
    protected function getMeta($context, $id, $actual)
    {
        $value = Value::find($id);

        if (!$value || $actual != $value->value) {
            $context = Context::where('context', $context)->first();

            $value = Value::where('context_id', $context->id)
                ->where('value', $actual)->first();

            if (!$value) {
                $value = Value::create(array(
                    'context_id' => $context->id,
                    'value' => $actual,
                ));
            }
        }

        return $value;
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model|null
     */
    protected function sessionDetail($id)
    {
        $session = $this->session->with($this->pdfRelations())->find($id);

        return $session;
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model|null
     */
    protected function sessionDetails($ids)
    {
        if (empty($ids)) {
            return new Collection();
        }

        $session = $this->session->with($this->pdfRelations())->whereIn('id', $ids)->get();

        return $session;
    }

    /**
     * @return array
     */
    protected function pdfRelations()
    {
        return [
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
        ];
    }

    /**
     * @param $session
     *
     * @return static
     */
    protected function pdfDocument($session)
    {
        /** @var PdfWrapper $snappy */
        $snappy = App::make('snappy.pdf.wrapper');

        $document = $snappy->loadView('instrument.pdf', ['session' => $session]);

        return $document;
    }

    /**
     * @param $session
     * @param $now
     *
     * @return string
     */
    protected function pdfName($session, $now)
    {
        $format = '%s-%s-%s.pdf';

        if ($session->user->fullname && $session->oudere->fullname) {
            $name = sprintf($format, $session->user->fullname, $session->oudere->fullname, $now);

            return $name;
        } elseif ($session->user->fullname) {
            $name = sprintf($format, $session->user->fullname, $session->oudere->identifier, $now);

            return $name;
        } elseif ($session->oudere->fullname) {
            $name = sprintf($format, $session->user->identifier, $session->oudere->fullname, $now);

            return $name;
        } else {
            $name = sprintf($format, $session->user->identifier, $session->oudere->identifier, $now);

            return $name;
        }
    }

    /**
     * @return Carbon|string
     */
    protected function pdfTimestamp()
    {
        $now = new Carbon();
        $now = $now->format('Y-m-d');

        return $now;
    }
}
