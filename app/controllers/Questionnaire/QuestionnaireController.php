<?php

namespace Questionnaire;

use View;
use Input;

class QuestionnaireController extends \AdminController{

    /**
     * @var Questionnaire
     */
    protected $questionnaire;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function index()
    {
        $questionnaires = $this->questionnaire->all();

        $this->layout->content = View::make('questionnaire.index', compact(array('questionnaires')))
            ->nest('questionnaireCreator', 'modals.questionnaire-creator')
            ->nest('panelCreator', 'modals.panel-creator');
    }

    public function store()
    {
        $validator = $this->questionnaire->validator();

        if($validator->fails())
        {
            return json_encode(array('status' => 'error', 'errors' => $validator->messages()->toArray()));
        }

        $questionnaire = $this->questionnaire->create(Input::all());

        return json_encode(array('status' => 'oke'));

    }

} 