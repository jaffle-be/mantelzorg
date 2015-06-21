<?php namespace App\Questionnaire\Export;

use Illuminate\Support\Collection;
use \App\Questionnaire\Questionnaire;

interface Exporter {

    /**
     * Generate the file for the survey that needs exporting.
     *
     * @param Questionnaire $survey
     * @param array         $filters
     *
     * @return string The filename
     */
    public function generate(Questionnaire $survey, array $filters = []);

}