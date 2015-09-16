<?php

namespace App\Http\Controllers;

use App\Organisation\OrganisationRepositoryInterface;
use App\Questionnaire\Export\Exporter;
use App\Questionnaire\Export\FileManager;
use App\Questionnaire\Questionnaire;
use App\UserRepositoryInterface;
use Input;
use Lang;
use Queue;
use Redirect;
use Response;
use Validator;

class RapportController extends AdminController
{

    protected $search;

    /**
     * @var Questionnaire
     */
    protected $questionnaire;

    /**
     * @var Exporter
     */
    protected $export;

    protected $users;

    protected $organisations;

    public function __construct(Questionnaire $questionnaire, FileManager $files, UserRepositoryInterface $users, OrganisationRepositoryInterface $organisations)
    {
        $this->middleware('auth.admin');

        $this->questionnaire = $questionnaire;
        $this->files = $files;
        $this->users = $users;
        $this->organisations = $organisations;
    }

    public function index()
    {
        $questionnaires = $this->questionnaire->orderBy('title')->get();

        $questionnaires = ['' => Lang::get('rapport.select_survey')] + $questionnaires->lists('title', 'id');

        $hulpverleners = $this->users->getForSelect();

        $organisations = $this->organisations->getForSelect();

        $files = $this->files->listFiles();

        return view('rapport.index', compact('questionnaires', 'files', 'hulpverleners', 'organisations'));
    }

    public function generate()
    {
        $id = Input::get('survey');

        $filters = Input::only(['hulpverlener_id', 'organisation_id']);

        $user = \Auth::user();

        $validator = Validator::make(Input::except('_token'), ['survey' => 'required|exists:questionnaires,id']);

        if ($validator->fails()) {
            return Redirect::back()->with('errors', $validator->messages())->withInput();
        }

        $this->dispatchFromArray('App\Questionnaire\Jobs\ExportJob', ['id' => $id, 'userid' => $user->id, 'filters' => $filters]);

        return Redirect::back()->with('success', \Lang::get('rapport.success'));
    }

    public function download($filename)
    {
        if ($this->files->exists($filename)) {
            return Response::download(app_path('storage') . '/exports/' . $filename);
        } else {
            return Redirect::route('rapport.index');
        }
    }

    public function delete($filename)
    {
        $this->files->delete($filename);

        return Redirect::back();
    }
}
