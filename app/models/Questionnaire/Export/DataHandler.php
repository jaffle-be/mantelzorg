<?php namespace Questionnaire\Export;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;

class DataHandler {

    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Collection $sessions, $panels, LaravelExcelWorksheet $sheet)
    {
        $sessions->load([
            'user',
            'oudere',
            'mantelzorger'
        ]);

        $sessionIds = $sessions->lists('id');

        $answers = $this->repository->getAnswers($sessionIds);
        $choises = $this->repository->getChoises($sessionIds);



        foreach ($sessions as $session) {

            $sessionAnswers = isset($answers[$session['id']]) ? $answers[$session['id']] : [];

            $sessionData = [];

            //add the session id as first column.
            $sessionData[] = $session->getAttribute('id');

            $sessionData = $this->getUserData($session, $sessionData);
            $sessionData = $this->getMantelzorgerData($session, $sessionData);
            $sessionData = $this->getOudereData($session, $sessionData);

            $session = $session->toArray();

            foreach ($panels as $panelid => $questions) {

                $sessionData = $this->answers($sessionData, $questions, $session, $sessionAnswers, $choises);
            }

            $sheet->appendRow($sessionData);

        }
    }

    protected function answers(array $data, $questions, $session, $answers, $chosen)
    {
        foreach ($questions as $question) {
            //check if the session answered the question
            if ($answer = $this->wasAnswered($answers, $question['id'])) {

                if($question['explainable'])
                {
                    $data[] = $answer->explanation;
                }

                foreach ($question['choises'] as $choise) {
                    if ($this->wasChecked($chosen, $choise['id'], $answer->id)) {
                        $data[] = 1;
                    } else {
                        $data[] = 0;
                    }
                }
            } else {

                if($question['explainable'])
                {
                    $data[] = '';
                }

                foreach ($question['choises'] as $choise) {
                    $data[] = 0;
                }
            }
        }

        return $data;
    }


    protected function wasAnswered(array $answers, $questionid)
    {
        return isset($answers[$questionid]) ? $answers[$questionid] : false;
    }

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

}