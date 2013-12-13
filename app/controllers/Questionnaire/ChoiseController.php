<?php

namespace Questionnaire;

use Input;

class ChoiseController extends \AdminController{

    /**
     * @var Choise
     */
    protected $choise;

    public function __construct(Choise $choise)
    {
        $this->choise = $choise;

        $this->beforeFilter('auth.admin');
    }


} 