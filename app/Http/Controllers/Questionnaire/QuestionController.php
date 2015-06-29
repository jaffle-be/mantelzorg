<?php

namespace App\Http\Controllers\Questionnaire;

use App\Questionnaire\Question;
use Input;

class QuestionController extends \App\Http\Controllers\AdminController
{

    /**
     * @var Question
     */
    protected $question;

    public function __construct(Question $question)
    {
        $this->question = $question;

        $this->middleware('auth.admin');
    }

    public function index($panel)
    {
        $panel->load(array(
            'questions'         => function ($query) {
                $query->orderBy('sort');
            },
            'questions.choises' => function ($query) {
                $query->orderBy('sort_weight');
            }
        ));

        return view('questionnaire.questions.index', compact(array('panel')))
            ->nest('questionCreator', 'modals.question-creator', compact(array()))
            ->nest('choiseCreator', 'modals.choise-creator', compact(array()));
    }

    public function store($panel)
    {

        $input = Input::all();
        $input = array_merge($input, array(
            'questionnaire_id'       => $panel->questionnaire->id,
            'questionnaire_panel_id' => $panel->id,
        ));

        $validator = $this->question->validator($input);

        if ($validator->fails()) {
            return json_encode(array(
                'status' => 'noke',
                'errors' => $validator->messages()->toArray()
            ));
        } else {
            $this->question->create($input);

            return json_encode(array(
                'status' => 'oke'
            ));
        }
    }

    public function update($panel, $question)
    {
        $question = $this->question->find($question);

        $fields = $this->fields();

        $validator = $this->question->validator(null, $fields);

        if ($validator->fails()) {
            return json_encode(array(
                'status' => 'noke',
                'errors' => $validator->messages()->toArray()
            ));
        }

        $question->update(Input::all());

        return json_encode(array(
            'status' => 'oke'
        ));
    }

    /**
     * since all updates only update 1 field, we can retrieve it by return the first key
     */
    protected function fields()
    {
        $input = Input::all();

        $input = array_keys($input);

        return array_pop($input);
    }
}