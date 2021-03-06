<?php

namespace App\Questionnaire\Export;

use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;
use App\Questionnaire\Panel;
use App\Questionnaire\Question;
use App\Questionnaire\Questionnaire;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;

class CsvExport implements Exporter
{
    protected $filter;

    /**
     * @var Excel
     */
    protected $excel;

    protected $carbon;

    protected $handler;

    protected $report;

    public function __construct(SessionFilter $filter, Excel $excel, Carbon $carbon, DataHandler $handler, Report $report)
    {
        ini_set('max_execution_time', 300);

        $this->filter = $filter;

        $this->excel = $excel;

        $this->carbon = $carbon;

        $this->handler = $handler;

        $this->report = $report;
    }

    /**
     * Elke vraag zal per mogelijk antwoord een kolom hebben.
     * Dan moet je per sessie per antwoord aanduiden of het was geselecteerd of niet.
     *
     * @param Questionnaire $survey
     * @param array         $filters
     *
     * @return string
     */
    public function generate(Questionnaire $survey, array $filters = [])
    {
        $this->boot($survey);

        $filename = $survey->title.'-'.$this->carbon->now()->format('y-m-d H:i:s');

        $excel = $this->excel->create($filename, function ($excel) use ($survey, $filters) {

            $excel->sheet($survey->title, function (LaravelExcelWorksheet $sheet) use ($survey, $filters) {
                //disable autosize for faster export.. (this dropped +-44 s with 200 sessions)
                $sheet->setAutoSize(false);
                //get the headers
                $headers = $this->headers($survey);

                //and add them
                $headers = $headers->toArray();

                $sheet->appendRow($headers);

                //now add all data rows
                $this->data($sheet, $survey, $filters);
            });
        });

        //do not change the value of the extension to xls
        //since that would only allow 256 columns
        $excel->store('xlsx');

        return $this->createReport($survey, $filters, $excel);
    }

    protected function headers(Questionnaire $survey)
    {
        $headers = new Collection();

        $headers->push('id');
        $headers->push('created');

        //add columns for people related info
        $headers = $this->headersHulpverlener($headers);
        $headers = $this->headersMantelzorger($headers);
        $headers = $this->headersOuderen($headers);

        $counter = 1;

        foreach ($survey->panels as $panel) {
            $this->panel($panel, $headers, $counter);
        }

        return $headers;
    }

    protected function panel(Panel $panel, Collection $headers, &$counter)
    {
        foreach ($panel->questions as $question) {
            if ($question->getAttribute('explainable')) {
                $headers->push($counter.'explanation');
            }

            $this->choises($headers, $question, $counter);

            ++$counter;
        }
    }

    protected function choises(Collection $headers, Question $question, $counter)
    {
        //checkboxed questions will have a column for each value
        if ($question->multiple_answer) {
            $options = 1;

            foreach ($question->choises as $choise) {
                $headers->push($counter.'option'.$options . ' (id: ' . $choise->id . ')');
                ++$options;
            }
        }
        //radios will have only 2 columns, one for the value, one for the id of that value
        else {
            $headers->push($counter.'option');
            $headers->push($counter.'option_id');
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

    protected function data(LaravelExcelWorksheet $sheet, Questionnaire $survey, array $filters)
    {
        $panels = $this->mapPanels($survey);

        $query = $this->filter->filter($survey, $filters);

        $query->chunk(100, function ($sessions) use ($panels, $sheet) {
            $this->handler->handle($sessions, $panels, $sheet);
        });
    }

    /**
     * @param $headers
     *
     * @return mixed
     */
    protected function headersHulpverlener($headers)
    {
        $user = new User();

        $keys = array_keys($user->toExportArray());

        return $headers->merge($keys);
    }

    /**
     * @param $headers
     *
     * @return mixed
     */
    protected function headersMantelzorger($headers)
    {
        $mantelzorger = new Mantelzorger();

        $keys = array_keys($mantelzorger->toExportArray());

        return $headers->merge($keys);
    }

    /**
     * @param $headers
     *
     * @return mixed
     */
    protected function headersOuderen($headers)
    {
        $oudere = new Oudere();

        $keys = array_keys($oudere->toExportArray());

        return $headers->merge($keys);
    }

    protected function boot(Questionnaire $survey)
    {
        $survey->load([
            'panels',
            //make sure questions follow the order of the questionnaire to number them in the report. not so transparent
            //but that is how they wanted it.
            'panels.questions',
            //same reasoning applies for the options available to a question.
            'panels.questions.choises',
        ])->all();
    }

    /**
     * @param Questionnaire $survey
     * @param array         $filters
     * @param               $excel
     *
     * @return Report
     */
    protected function createReport(Questionnaire $survey, array $filters, $excel)
    {
        $report = $this->report->newInstance();

        $report->filename = $excel->getFileName().'.xlsx';

        $report->questionnaire()->associate($survey);

        if (isset($filters['hulpverlener_id']) && !empty($filters['hulpverlener_id'])) {
            $report->user_id = $filters['hulpverlener_id'];
        }

        if (isset($filters['organisation_id']) && !empty($filters['organisation_id'])) {
            $report->organisation_id = $filters['organisation_id'];
        }

        $report->survey_count = $this->sessionCount($survey, $filters);

        $report->save();

        return $report;
    }

    protected function sessionCount(Questionnaire $survey, array $filters)
    {
        $query = $this->filter->filter($survey, $filters);

        return $query->count();
    }
}
