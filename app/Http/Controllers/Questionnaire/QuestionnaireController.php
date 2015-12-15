<?php

namespace App\Http\Controllers\Questionnaire;

use App\Questionnaire\Questionnaire;
use Illuminate\Contracts\Validation\Factory;
use Input;

class QuestionnaireController extends \App\Http\Controllers\AdminController
{
    /**
     * @var Questionnaire
     */
    protected $questionnaire;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;

        $this->middleware('auth.admin');
    }

    public function index()
    {
        $questionnaires = $this->questionnaire->with(['panels'])->get();

        return view('questionnaire.index', compact('questionnaires'));
    }

    public function store(Factory $validator)
    {
        $validator = $validator->make(Input::all(), $this->questionnaire->rules());

        if ($validator->fails()) {
            return json_encode(array('status' => 'error', 'errors' => $validator->messages()->toArray()));
        }

        $this->questionnaire->create(Input::all());

        return json_encode(array('status' => 'oke'));
    }

    public function update(Questionnaire $survey, Factory $validator)
    {
        $validator = $validator->make(Input::all(), $survey->rules(array_keys(Input::all()), []));

        if ($validator->fails()) {
            return $validator->messages();
        } else {
            $survey->update(Input::all());

            return json_encode(array('status' => 'oke'));
        }
    }
}
