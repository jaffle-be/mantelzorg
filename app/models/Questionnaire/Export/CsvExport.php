<?php namespace Questionnaire\Export;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;
use Questionnaire\Panel;
use Questionnaire\Question;
use Questionnaire\Questionnaire;

class CsvExport implements Exporter
{

    /**
     * @var Excel
     */
    protected $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    /**
     * Elke vraag zal per mogelijk antwoord een kolom hebben.
     * Dan moet je per sessie per antwoord aanduiden of het was geselecteerd of niet.
     *
     * @param Questionnaire $survey
     * @param Collection    $sessions
     */
    public function generate(Questionnaire $survey, Collection $sessions)
    {
        $data = new Collection();

        $headers = $this->headers($survey);

        $data->push($headers);

        $this->data($data, $survey);

        $this->excel->create($survey->title, function($excel) use ($data)
        {
            $excel->sheet($excel->getTitle(), function($sheet) use ($data)
            {
                $sheet->fromArray($data->toArray(), null, 'A1', true, false);
            });
        })->download();
    }

    protected function headers(Questionnaire $survey)
    {
        $headers = new Collection();

        $headers->push('id');

        foreach($survey->panels as $panel)
        {
            $this->panel($panel, $headers);
        }

        return $headers;

    }

    protected function panel(Panel $panel, Collection $headers)
    {
        foreach($panel->questions as $question)
        {
            $this->choises($headers, $question);
        }
    }

    protected function choises(Collection $headers, Question $question)
    {
        foreach($question->choises as $choise)
        {
            $headers->push($question->id . 'option' . $choise->id);
        }
    }

    protected function data($data, $survey)
    {
        foreach($survey->sessions as $session)
        {
            $sessionData = new Collection();

            //add the session id as first column.
            $sessionData->push($session->id);

            foreach($survey->panels as $panel)
            {
                $this->answers($sessionData, $panel, $session);
            }

            $data->push($sessionData);
        }
    }

    protected function answers(Collection $data, $panel, $session)
    {
        foreach($panel->questions as $question)
        {
            if($answers = $session->getAnswered($question))
            {
                foreach($question->choises as $choise)
                {
                    if($answers->wasChecked($choise))
                    {
                        $data->push(1);
                    }
                    else{
                        $data->push(0);
                    }
                }
            }
            else{
                foreach($question->choises as $choise)
                {
                    $data->push(0);
                }
            }
        }
    }
}