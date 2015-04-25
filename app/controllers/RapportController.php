<?php

use Questionnaire\Export\Exporter;
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

    public function __construct(Questionnaire $questionnaire, Exporter $export)
    {
        $this->beforeFilter('auth.admin');

        $this->questionnaire = $questionnaire;

        $this->export = $export;
    }

    public function index()
    {
        $questionnaires = $this->questionnaire->orderBy('title')->get();

        $questionnaires = ['' => Lang::get('rapport.select_survey')] + $questionnaires->lists('title', 'id');

        //this should list downloads.
        $exports = scandir(app_path('storage') . '/exports');

        $files = [];

        while($file = array_shift($exports))
        {
            if(strpos($file, '.') !== 0)
            {
                array_push($files, $file);
            }
        }

        $this->layout->content = View::make('rapport.index', compact('questionnaires', 'files'));
    }

    public function generate()
    {
        $id = Input::get('survey');

        $validator = Validator::make(Input::except('_token'), ['survey' => 'required|exists:questionnaires,id']);

        if ($validator->fails()) {
            return Redirect::back()->with('errors', $validator->messages());
        }

        $survey = $this->questionnaire->find($id);

        $survey->load([
            'panels',
            //make sure questions follow the order of the questionnaire to number them in the report. not so transparent
            //but that is how they wanted it.
            'panels.questions' => function($query){
                $query->orderBy('sort');
            },
            //same reasoning applies for the options available to a question.
            'panels.questions.choises' => function($query){
                $query->orderBy('sort_weight');
            }
        ])->all();

        $this->export->generate($survey);

        return Redirect::back();
    }

    public function download($filename)
    {
        return Response::download(app_path('storage') . '/exports/' . $filename);
    }
}
