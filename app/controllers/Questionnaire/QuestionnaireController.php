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

    }

} 