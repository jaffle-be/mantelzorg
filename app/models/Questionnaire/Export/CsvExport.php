<?php namespace Questionnaire\Export;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Mantelzorger\Mantelzorger;
use Mantelzorger\Oudere;
use Questionnaire\Panel;
use Questionnaire\Question;
use Questionnaire\Questionnaire;
use User;

class CsvExport implements Exporter
{

    /**
     * @var Excel
     */
    protected $excel;

    public function __construct(Excel $excel, Carbon $carbon, DataHandler $handler)
    {
        ini_set('max_execution_time', 300);

        $this->excel = $excel;

        $this->carbon = $carbon;

        $this->handler = $handler;
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
        $filename = $survey->title . '-' . $this->carbon->now()->format('y-m-d H:i:s');

        $excel = $this->excel->create($filename, function ($excel) use ($survey) {

            $excel->sheet($survey->title, function (LaravelExcelWorksheet $sheet) use ($survey) {
                //disable autosize for faster export.. (this dropped +-44 s with 200 sessions)
                $sheet->setAutoSize(false);
                //get the headers
                $headers = $this->headers($survey);

                //and add them
                $headers = $headers->toArray();

                $sheet->appendRow($headers);

                //now add all data rows
                $this->data($sheet, $survey);
            });
        });

        //do not change the value of the extension to xls, since that will only allow 256 columns
        $excel->store('xlsx');

        return $excel->getFileName() . '.xlsx';
    }

    protected function headers(Questionnaire $survey)
    {
        $headers = new Collection();

        $headers->push('id');

        //add columns for people related info
        $headers = $this->headersHulpverlener($headers);
        $headers = $this->headersMantelzorger($headers);
        $headers = $this->headersOuders($headers);

        $counter = 1;

        foreach ($survey->panels as $panel) {
            $this->panel($panel, $headers, $counter);
        }

        return $headers;
    }

    protected function panel(Panel $panel, Collection $headers, &$counter)
    {
        foreach ($panel->questions as $question) {

            if($question->getAttribute('explainable'))
            {
                $headers->push($counter . 'explanation');
            }

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

    protected function data(LaravelExcelWorksheet $sheet, Questionnaire $survey)
    {
        $panels = $this->mapPanels($survey);

        $survey->sessions()->chunk(100, function ($sessions) use ($panels, $sheet) {

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
    protected function headersOuders($headers)
    {
        $oudere = new Oudere();

        $keys = array_keys($oudere->toExportArray());

        return $headers->merge($keys);
    }
}