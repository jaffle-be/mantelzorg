<?php

namespace App\Http\Controllers\Questionnaire;

use App\Questionnaire\Questionnaire;
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
        $questionnaires = $this->questionnaire->with(array(
            'panels' => function ($query) {
                $query->orderBy('panel_weight');
            }
        ))->get();

        return view('questionnaire.index', compact('questionnaires'))
            ->nest('questionnaireCreator', 'modals.questionnaire-creator')
            ->nest('panelCreator', 'modals.panel-creator');
    }

    public function store()
    {
        $validator = $this->questionnaire->validator();

        if ($validator->fails()) {
            return json_encode(array('status' => 'error', 'errors' => $validator->messages()->toArray()));
        }

        $questionnaire = $this->questionnaire->create(Input::all());

        return json_encode(array('status' => 'oke'));
    }

    public function update($questionnaire)
    {
        $questionnaire = $this->questionnaire->find($questionnaire);

        $validator = $this->questionnaire->validator(null, Input::has('title') ? 'title' : 'active');

        if ($validator->fails()) {
            return $validator->messages();
        } else {
            $questionnaire->update(Input::all());

            return json_encode(array('status' => 'oke'));
        }
    }
}
