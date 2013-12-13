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

    public function store($question)
    {
        $input = Input::all();
        $input = array_merge($input, array(
            'question_id' => $question->id
        ));

        $validator = $this->choise->validator($input, array('title', 'value', 'question_id'));

        if($validator->fails())
        {
            return json_encode(array(
                'status' => 'noke',
                'errors' => $validator->messages()->toArray()
            ));
        }

        $this->choise->create($input);

        return json_encode(array(
            'status' => 'oke'
        ));
    }

} 