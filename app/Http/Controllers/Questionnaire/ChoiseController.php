<?php

namespace App\Http\Controllers\Questionnaire;

use App\Questionnaire\Choise;
use App\Questionnaire\Question;
use Illuminate\Contracts\Validation\Factory;
use Input;

class ChoiseController extends \App\Http\Controllers\AdminController
{
    /**
     * @var Choise
     */
    protected $choise;

    public function __construct(Choise $choise)
    {
        $this->choise = $choise;

        $this->middleware('auth.admin');
    }

    public function store($question, Factory $validator)
    {
        $choises = $question->choises;
        $sort = count($choises) * 10;
        $input = Input::all();
        $input = array_merge($input, array(
            'question_id' => $question->id,
            'sort_weight' => $sort,
        ));

        $validator = $validator->make($input, $this->choise->rules());

        if ($validator->fails()) {
            return json_encode(array(
                'status' => 'noke',
                'errors' => $validator->messages()->toArray(),
            ));
        }

        $this->choise->create($input);

        return json_encode(array(
            'status' => 'oke',
        ));
    }

    public function update(Question $question, Choise $choise, Factory $validator)
    {
        if ($choise) {
            $input = Input::all();

            $validator = $validator->make($input, $this->choise->rules(array_keys($input)));

            if ($validator->fails()) {
                return json_encode(array(
                    'status' => 'noke',
                    'errors' => $validator->messages()->toArray(),
                ));
            }

            $choise->update(array_except($input, '_method'));

            return json_encode(array(
                'status' => 'oke',
            ));
        }
    }

    public function sort($question)
    {
        $positions = Input::get('positions');

        $positions = array_map(function ($item) {
            return str_replace('choise-', '', $item);
        }, $positions);

        $question->load('choises');

        foreach ($positions as $position => $id) {
            $choise = $question->choises->find($id);

            $choise->sort_weight = $position * 10;

            $choise->save();
        }

        return json_encode(array(
            'status' => 'oke',
        ));
    }

    protected function field()
    {
        $input = Input::all();

        $input = array_keys($input);

        return array_pop($input);
    }
}
