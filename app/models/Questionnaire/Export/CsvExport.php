<?php namespace Questionnaire\Export;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
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

    protected $repository;

    public function __construct(Excel $excel, Repository $repo, Carbon $carbon)
    {
        ini_set('max_execution_time', 300);

        $this->excel = $excel;

        $this->repository = $repo;

        $this->carbon = $carbon;
    }

    /**
     * Elke vraag zal per mogelijk antwoord een kolom hebben.
     * Dan moet je per sessie per antwoord aanduiden of het was geselecteerd of niet.
     *
     * @param Questionnaire $survey
     * @param Collection    $sessions
     */
    public function generate(Questionnaire $survey)
    {
        $excel = $this->excel->create($survey->title . '-' . $this->carbon->now()->format('y-m-d H:i:s'), function ($excel) use ($survey) {

            $excel->sheet($survey->title, function (LaravelExcelWorksheet $sheet) use ($survey) {
                //disable autosize for faster export.. (this dropped +-44 s with 200 sessions)
                $sheet->setAutoSize(false);
                //get the headers
                $headers = $this->headers($survey);
                //and add them
                $sheet->appendRow($headers->toArray());
                //now add all data rows
                $this->data($sheet, $survey);
            });
        });

        $excel->store('xls');

        return $excel->getFileName();
    }

    protected function headers(Questionnaire $survey)
    {
        $headers = new Collection();

        $headers->push('id');

        $counter = 1;

        foreach ($survey->panels as $panel) {
            $this->panel($panel, $headers, $counter);
        }

        return $headers;
    }

    protected function panel(Panel $panel, Collection $headers, &$counter)
    {
        foreach ($panel->questions as $question) {
            $this->choises($headers, $question, $counter);
            $counter++;
        }
    }

    protected function choises(Collection $headers, Question $question, $counter)
    {
        $options = 1;

        foreach ($question->choises as $choise) {
            $headers->push($counter . 'option' . $options);
            $options++;
        }
    }

    protected function mapPanels(Questionnaire $survey)
    {
        $panels = $survey->getAttribute('panels');

        $panels = $panels->toArray();

        $map = [];

        foreach ($panels as $panel) {
            $map[$panel['id']] = $panel['questions'];
        }

        return $map;
    }

    protected function data(LaravelExcelWorksheet $sheet, $survey)
    {
        $repository = $this->repository;

        $panels = $this->mapPanels($survey);

        $survey->sessions()->chunk(100, function ($sessions) use ($panels, $sheet, $repository) {

            $answers = $repository->getAnswers($sessions->lists('id'));

            $choises = $repository->getChoises(array_pluck($answers, 'id'));

            foreach ($sessions as $session) {
                $sessionData = [];

                //add the session id as first column.
                $sessionData[] = $session->getAttribute('id');

                $session = $session->toArray();

                foreach ($panels as $panelid => $questions) {
                    $sessionData = $this->answers($sessionData, $questions, $session, $answers, $choises);
                }

                $sheet->appendRow($sessionData);
            }
        });
    }

    protected function answers(array $data, $questions, $session, $answers, $chosen)
    {
        foreach ($questions as $question) {
            //check if the session answered the question
            if ($answer = $this->wasAnswered($answers, $question['id'])) {

                foreach ($question['choises'] as $choise) {
                    if ($this->wasChecked($chosen, $choise['id'], $answer->id)) {
                        $data[] = 1;
                    } else {
                        $data[] = 0;
                    }
                }
            } else {
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
}