<?php

namespace Questionnaire;

use View;

class QuestionController extends \AdminController{

    public function __construct()
    {
        $this->beforeFilter('auth.admin');
    }

    public function index()
    {
        $this->layout->content = View::make('questionnaire.questions.index');
    }

} 