<?php

use Questionnaire\Export\Exporter;
use Questionnaire\Export\FileManager;
use Questionnaire\Questionnaire;

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

    /**
     * @var AuthManager
     */
    protected $auth;

    public function __construct(Questionnaire $questionnaire, FileManager $files)
    {
        $this->beforeFilter('auth.admin');

        $this->questionnaire = $questionnaire;
        $this->files = $files;
    }

    public function index()
    {
        $questionnaires = $this->questionnaire->orderBy('title')->get();

        $questionnaires = ['' => Lang::get('rapport.select_survey')] + $questionnaires->lists('title', 'id');

        $files = $this->files->listFiles();

        $this->layout->content = View::make('rapport.index', compact('questionnaires', 'files'));
    }

    public function generate()
    {
        $id = Input::get('survey');

        $user = \Auth::user();

        $validator = Validator::make(Input::except('_token'), ['survey' => 'required|exists:questionnaires,id']);

        if ($validator->fails()) {
            return Redirect::back()->with('errors', $validator->messages());
        }

        Queue::push('Questionnaire\Jobs\ExportJob@fire', ['id' => $id, 'userid' => $user->id]);

        return Redirect::back()->with('success', \Lang::get('rapport.success'));
    }

    public function download($filename)
    {
        if($this->files->exists($filename))
        {
            return Response::download(app_path('storage') . '/exports/' . $filename);
        }
        else
        {
            return Redirect::route('rapport.index');
        }

    }

    public function delete($filename)
    {
        $this->files->delete($filename);

        return Redirect::back();
    }
}
