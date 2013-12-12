<?php

namespace Questionnaire;

use Input;
use View;

class PanelController extends \AdminController{

    /**
     * @var Panel
     */
    protected $panel;

    public function __construct(Panel $panel)
    {
        $this->panel = $panel;
    }

    public function store($questionnaire)
    {
        $input = Input::all();

        $input['questionnaire_id'] = $questionnaire->id;

        //find heighest weight, add 10 to it, if no records -> start with 0;
        $panels = $questionnaire->panels->sortBy(function($panel)
        {
            return $panel->panel_weight;
        }, 'desc');

        $heighestPanel = $panels->last();

        $input['panel_weight'] = $heighestPanel ? $heighestPanel->panel_weight + 10 : 0;

        $validator = $this->panel->validator($input);

        if($validator->fails())
        {
            return json_encode(array(
                'status' => 'error'
            ));
        }
        else
        {
            $this->panel->create($input);

            return json_encode(array(
                'status' => 'oke'
            ));
        }

    }

    public function update($questionnaire, $panel)
    {
        $panel = $this->panel->find($panel);

        $validator = $this->panel->validator();

        if($validator->fails())
        {
            return $validator->messages();
        }
        else
        {
            $panel->update(Input::all());

            return json_encode(array('status' => 'oke'));
        }
    }

    public function sort($questionnaire)
    {
        $positions = Input::get('positions');

        //remove 'panel-' prefix
        $positions = array_map(function($item){
            return str_replace('panel-', '', $item);
        }, $positions);

        if($positions)
        {
            $panels = $this->panel->whereIn('id', $positions)->get();

            foreach($panels as $panel)
            {
                $key = array_search($panel->id, $positions);

                $panel->panel_weight = $key * 10;

                $panel->save();
            }
        }

        return json_encode(array('status' => 'oke'));
    }

} 