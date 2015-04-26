<?php namespace Questionnaire\Export;

use Illuminate\Support\Collection;
use Questionnaire\Questionnaire;

interface Exporter {

    /**
     * Generate the file for the survey that needs exporting.
     *
     * @param Questionnaire $survey
     * @return string The filename
     */
    public function generate(Questionnaire $survey);

}