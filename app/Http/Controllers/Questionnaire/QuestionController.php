<?php

namespace App\Http\Controllers\Questionnaire;

use App\Questionnaire\Panel;
use App\Questionnaire\Question;
use Illuminate\Validation\Factory;
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

        return view('questionnaire.questions.index', compact('panel'));
    }

    public function store(Panel $panel, Factory $validator)
    {
        $validator = $validator->make(Input::all(), $this->question->rules(array_keys(Input::all()), [
            'questionnaire_id'       => $panel->questionnaire->id,
            'questionnaire_panel_id' => $panel->id,
        ]));

        $input = Input::all();

        $input = array_merge($input, array(
            'questionnaire_id'       => $panel->questionnaire->id,
            'questionnaire_panel_id' => $panel->id,
        ));

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

    public function update(Panel $panel, Question $question, Factory $validator)
    {
        $question = $this->question->find($question);

        $validator = $validator->make(Input::all(), $question->rules(array_keys(Input::all())));

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

}