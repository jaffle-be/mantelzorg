<?php namespace Questionnaire\Export;

class DataGenerator {

    public function generate(Questionnaire $survey, Session $session)
    {
        $data = new Collection();

        foreach($survey->panels as $panel)
        {
            foreach($panel->questions as $question)
            {
                if($question->wasAnswerd())
                {

                }

                $this->answers($data, $question, $session);
            }
        }

        return $data;
    }

    protected function answers($data, $question, $session)
    {
        foreach($question->choises as $choise)
        {
            $choise->
        }
    }
}