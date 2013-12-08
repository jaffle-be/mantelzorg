<?php

namespace Questionnaire;

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
            ->nest('questionnaireCreator', 'modals.questionnaire-creator');
    }

    }

} 