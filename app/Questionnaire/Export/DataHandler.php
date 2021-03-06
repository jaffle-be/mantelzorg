<?php

namespace App\Questionnaire\Export;

use App\Questionnaire\Session;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;

class DataHandler
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $relations = [
        'user',
        'user.organisation',
        'user.organisation_location',
        'oudere',
        'oudere.mantelzorgerRelation',
        'oudere.oorzaakHulpbehoefte',
        'oudere.woonSituatie',
        'oudere.belProfiel',
        'mantelzorger',
    ];

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Collection            $sessions
     * @param array                 $panels
     * @param LaravelExcelWorksheet $sheet
     */
    public function handle(Collection $sessions, array $panels, LaravelExcelWorksheet $sheet)
    {
        list($answers, $choises) = $this->boot($sessions);

        foreach ($sessions as $session) {
            $sessionAnswers = isset($answers[$session['id']]) ? $answers[$session['id']] : [];

            $sessionData = $this->getBaseData($session);

            $session = $session->toArray();

            foreach ($panels as $panelid => $questions) {
                $sessionData = $this->answers($sessionData, $questions, $session, $sessionAnswers, $choises);
            }

            $sheet->appendRow($sessionData);
        }
    }

    /**
     * @param array $data
     * @param       $questions
     * @param       $session
     * @param       $answers
     * @param       $chosen
     *
     * @return array
     */
    protected function answers(array $data, $questions, $session, $answers, $chosen)
    {
        foreach ($questions as $question) {
            //check if the session answered the question
            if ($answer = $this->wasAnswered($answers, $question['id'])) {
                $data = $this->handleAnswered($data, $chosen, $question, $answer);
            } else {
                $data = $this->handleUnanswered($data, $question);
            }
        }

        return $data;
    }

    /**
     * @param array $answers
     * @param       $questionid
     *
     * @return bool
     */
    protected function wasAnswered(array $answers, $questionid)
    {
        return isset($answers[$questionid]) ? $answers[$questionid] : false;
    }

    /**
     * @param $choises
     * @param $choiseid
     * @param $answerid
     *
     * @return bool
     */
    protected function wasChecked($choises, $choiseid, $answerid)
    {
        return isset($choises[$answerid]) && in_array($choiseid, $choises[$answerid]);
    }

    /**
     * @param $session
     * @param $sessionData
     *
     * @return array
     */
    protected function getUserData($session, $sessionData)
    {
        $user = $session->user->toExportArray();
        $sessionData = array_merge($sessionData, array_values($user));

        return $sessionData;
    }

    /**
     * @param $session
     * @param $sessionData
     *
     * @return array
     */
    protected function getMantelzorgerData($session, $sessionData)
    {
        $mantelzorger = $session->mantelzorger->toExportArray();
        $sessionData = array_merge($sessionData, array_values($mantelzorger));

        return $sessionData;
    }

    /**
     * @param $session
     * @param $sessionData
     *
     * @return array
     */
    protected function getOudereData($session, $sessionData)
    {
        $oudere = $session->oudere->toExportArray();
        $sessionData = array_merge($sessionData, array_values($oudere));

        return $sessionData;
    }

    /**
     * @param $session
     *
     * @return array
     */
    protected function getBaseData(Session $session)
    {
        $sessionData = [];
        //add the session id as first column.
        //the created_at date as the second column.
        $sessionData[] = $session->getAttribute('id');
        $sessionData[] = $session->created_at->format('Y-m-d H:i:s');

        $sessionData = $this->getUserData($session, $sessionData);
        $sessionData = $this->getMantelzorgerData($session, $sessionData);
        $sessionData = $this->getOudereData($session, $sessionData);

        return $sessionData;
    }

    /**
     * @param array $data
     * @param       $chosen
     * @param       $question
     * @param       $answer
     *
     * @return array
     */
    protected function handleAnswered(array $data, $chosen, $question, $answer)
    {
        if ($question['explainable']) {
            $data[] = $answer->explanation;
        }

        if ($question['multiple_answer']) {
            foreach ($question['choises'] as $choise) {
                if ($this->wasChecked($chosen, $choise['id'], $answer->id)) {
                    $data[] = 1;
                } else {
                    $data[] = 0;
                }
            }
        } elseif ($question['multiple_choise']) {
            //find answered

            $found = false;
            $counter = 0;

            while (!$found && $counter < count($question['choises'])) {
                $choise = $question['choises'][$counter];

                if ($this->wasChecked($chosen, $choise['id'], $answer->id)) {
                    $found = $choise;
                }

                ++$counter;
            }

            if ($found) {
                //first add the value then the corresponding id
                $data[] = $found['title'];
                $data[] = $found['id'];
            } else {
                $data[] = null;
                $data[] = null;
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @param       $question
     *
     * @return array
     */
    protected function handleUnanswered(array $data, $question)
    {
        if ($question['explainable']) {
            $data[] = '';
        }

        if ($question['multiple_answer']) {
            //checkboxed question
            foreach ($question['choises'] as $choise) {
                $data[] = 0;
            }
        } elseif ($question['multiple_choise']) {
            //radio question needs 2 columns
            $data[] = null;
            $data[] = null;
        }

        return $data;
    }

    /**
     * @param Collection $sessions
     *
     * @return array
     */
    protected function boot(Collection $sessions)
    {
        $sessions->load($this->relations);

        $sessionIds = $sessions->pluck('id')->all();

        $answers = $this->repository->getAnswers($sessionIds);
        $choises = $this->repository->getChoises($sessionIds);

        return array($answers, $choises);
    }
}
