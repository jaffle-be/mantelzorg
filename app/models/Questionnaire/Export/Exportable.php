<?php namespace Questionnaire\Export;

interface Exportable {

    /**
     * @return array
     */
    public function toExportArray();
}