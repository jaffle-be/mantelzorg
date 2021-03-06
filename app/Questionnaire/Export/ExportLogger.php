<?php

namespace App\Questionnaire\Export;

use App\Questionnaire\Questionnaire;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\Writer;

class ExportLogger
{
    protected $log;

    protected $start;

    protected $db;

    public function __construct(Writer $log, DatabaseManager $db)
    {
        $this->log = $log;

        $this->db = $db;
    }

    public function start($id)
    {
        $this->log->info('job started for '.$id);

        $this->db->connection()->enableQueryLog();

        $this->start = microtime(true);
    }

    public function stop(Questionnaire $survey)
    {
        $time = microtime(true) - $this->start;

        $log = $this->db->connection()->getQueryLog();

        $this->log->info(sprintf('generating export for %s took %d seconds', $survey->title, $time));
        $this->log->info(sprintf('we made %d database calls during the export', count($log)));
    }

    public function error(Exception $e)
    {
        $message = sprintf('job failed: %s error: %s in file %s on line %d', __CLASS__, $e->getMessage(), $e->getFile(), $e->getLine());

        $this->log->error($message);
    }
}
