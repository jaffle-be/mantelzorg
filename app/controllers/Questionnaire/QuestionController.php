<?php

namespace Questionnaire;

use View;
use Input;

class QuestionController extends \AdminController{

    /**
     * @var Question
     */
    protected $question;

    public function __construct(Question $question)
    {
        $this->question = $question;

        $this->beforeFilter('auth.admin');
    }

    public function index($panel)
    {
        $this->layout->content = View::make('questionnaire.questions.index', compact(array('panel')))
            ->nest('questionCreator', 'modals.question-creator', compact(array()))
            ->nest('choiseCreator', 'modals.choise-creator', compact(array()));
    }

    public function store($panel)
    {

        $input = Input::all();
        $input = array_merge($input, array(
            'questionnaire_id' => $panel->questionnaire->id,
            'questionnaire_panel_id' => $panel->id,
        ));

        $validator = $this->question->validator($input);

        if($validator->fails())
        {
            return json_encode(array(
                'status' => 'noke',
                'errors' => $validator->messages()->toArray()
            ));
        }

        else
        {
            $this->question->create($input);

            return json_encode(array(
                'status' => 'oke'
            ));
        }
    }

} 