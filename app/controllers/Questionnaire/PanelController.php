<?php

namespace Questionnaire;

use View;

class PanelController extends \AdminController{

    public function index($questionnaire)
    {
        $panels = $questionnaire->panels;

        $this->layout->content = View::make('questionnaire.panels.index');
    }

} 