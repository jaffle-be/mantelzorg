<?php namespace App\Questionnaire\Export;

use Illuminate\Database\Connection;
use App\Questionnaire\Answer;
use App\Questionnaire\Choise;

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

    public function getChoises($sessions)
    {
        if(empty($sessions))
            return array();

        $query = $this->tableChoise();

        $query->leftJoin('questionnaire_answers', 'questionnaire_answers.id', '=', 'questionnaire_answer_choises.answer_id');

        $query->whereIn('questionnaire_answers.session_id', $sessions);

        $result = $query->get(['questionnaire_answer_choises.answer_id', 'questionnaire_answer_choises.choise_id']);

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
            $answered[$answer->session_id][$answer->question_id] = $answer;
        });

        return $answered;
    }
}