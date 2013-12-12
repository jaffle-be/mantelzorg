<?php

namespace Questionnaire;

class QuestionController extends \AdminController{

    public function __construct()
    {
        $this->beforeFilter('auth.admin');
    }

    public function index()
    {
        return 'test';
    }

} 