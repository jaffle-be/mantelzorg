<?php namespace App\Questionnaire\Export;

interface Exportable {

    /**
     * @return array
     */
    public function toExportArray();
}