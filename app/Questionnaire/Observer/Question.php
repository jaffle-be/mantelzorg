<?php

namespace App\Questionnaire\Observer;

use App\Questionnaire\Question as QuestionModel;
use Illuminate\Events\Dispatcher;

class Question
{
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    protected $questions;

    public function __construct(Dispatcher $events, QuestionModel $questions)
    {
        $this->events = $events;
        $this->questions = $questions;
    }

    /**
     * @param $model
     */
    public function creating($model)
    {
        //because we had to rearrange questions, we added a sort field, but it needs autoupdating per panel.
        $sort = $model->where('questionnaire_panel_id', $model->questionnaire_panel_id)->max('sort');

        if ($sort == null) {
            $sort = 0;
        }

        $model->sort = $sort + 1;
    }

    public function saving($model)
    {
        if ($model->isDirty('multiple_choise') && $model->multiple_choise == '0') {
            $model->multiple_answer = 0;
        }
    }
}
