<?php namespace Questionnaire\Export;

use Illuminate\Database\Connection;
use Questionnaire\Answer;
use Questionnaire\Choise;

class Repository
{

    /**
     * @var Answer
     */
    protected $answer;

    /**
     * @var Choise
     */
    protected $choise;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Answer $answer, Choise $choise, Connection $connection)
    {
        $this->answer = $answer;
        $this->choise = $choise;
        $this->connection = $connection;
    }

    protected function tableAnswer()
    {
        return $this->connection->table($this->answer->getTable());
    }

    protected function tableChoise()
    {
        return $this->connection->table($this->answer->choises()->getTable());
    }

    public function getAnswers($sessions)
    {
        if (empty($sessions)) {
            return array();
        }

        $query = $this->tableAnswer();

        $query->whereIn('session_id', $sessions)
            ->orderBy('session_id');

        $result = $query->get([
            'id', 'session_id', 'question_id', 'explanation',
        ]);

        return $this->mapAnswers($result);
    }

    public function getChoises($answerids)
    {
        if(empty($answerids))
            return array();

        $query = $this->tableChoise();

        $query->whereIn('answer_id', $answerids);

        $result =  $query->get(['answer_id', 'choise_id']);

        return $this->mapChoises($result);
    }

    protected function mapChoises(array $choises)
    {
        $chosen = [];

        array_walk($choises, function ($choise) use (&$chosen) {

            if (!isset($chosen[$choise->answer_id])) {
                $chosen[$choise->answer_id] = [];
            }

            array_push($chosen[$choise->answer_id], $choise->choise_id);
        });

        return $chosen;
    }

    protected function mapAnswers(array $answers)
    {
        $answered = [];

        array_walk($answers, function ($answer) use (&$answered) {
            $answered[$answer->question_id] = $answer;
        });

        return $answered;
    }
}