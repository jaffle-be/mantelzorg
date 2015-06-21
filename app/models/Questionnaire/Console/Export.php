<?php namespace App\Questionnaire\Console;

use Illuminate\Console\Command;
use \App\Questionnaire\Export\Exporter;
use \App\Questionnaire\Questionnaire;
use Symfony\Component\Console\Input\InputArgument;

class Export extends Command
{

    protected $name = 'instrument:export';

    /**
     * @var Exporter
     */
    protected $exporter;

    /**
     * @var Questionnaire
     */
    protected $questionnaire;

    public function __construct(Exporter $exporter, Questionnaire $questionnaire)
    {
        $this->exporter = $exporter;
        $this->questionnaire = $questionnaire;

        parent::__construct();
    }

    public function fire()
    {
        $id = $this->argument('id');

        $survey = $this->questionnaire->find($id);

        $this->exporter->generate($survey);
    }

    protected function getArguments()
    {
        return [
            ['id', InputArgument::REQUIRED, 'the id of the survey to export']
        ];
    }
}