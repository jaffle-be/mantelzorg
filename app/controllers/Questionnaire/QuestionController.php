<?php

namespace Questionnaire;

use View;

class QuestionController extends \AdminController{

    public function __construct()
    {
        $this->beforeFilter('auth.admin');
    }

    public function index($panel)
    {
        $this->layout->content = View::make('questionnaire.questions.index', compact(array('panel')));
    }

} 