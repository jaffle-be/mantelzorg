<?php

namespace App\Http\Controllers\Questionnaire;

use App\Questionnaire\Panel;
use App\Questionnaire\Questionnaire;
use Illuminate\Contracts\Validation\Factory;
use Input;

class PanelController extends \App\Http\Controllers\AdminController
{

    /**
     * @var Panel
     */
    protected $panel;

    public function __construct(Panel $panel)
    {
        $this->panel = $panel;

        $this->middleware('auth.admin');
    }

    public function store(Questionnaire $survey, Factory $validator)
    {
        $input = Input::all();

        $input['questionnaire_id'] = $survey->id;

        //find heighest weight, add 10 to it, if no records -> start with 0;
        $panels = $survey->panels->sortBy(function ($panel) {
            return $panel->panel_weight;
        }, SORT_REGULAR, true);

        $heighestPanel = $panels->last();

        $input['panel_weight'] = $heighestPanel ? $heighestPanel->panel_weight + 10 : 0;

        $validator = $validator->make($input, $this->panel->rules([], [
            'questionnaire' => $survey->id
        ]));

        if ($validator->fails()) {
            return json_encode(array(
                'status' => 'error',
                'errors' => $validator->messages()->toArray()
            ));
        } else {
            $this->panel->create($input);

            return json_encode(array(
                'status' => 'oke'
            ));
        }
    }

    public function update(Questionnaire $survey, Panel $panel, Factory $validator)
    {
        $validator = $validator->make(Input::all(), $this->panel->rules(array_keys(Input::all()), [
            'questionnaire' => $survey->id,
            'panel' => $panel->id
        ]));

        if ($validator->fails()) {
            return $validator->messages();
        } else {
            $panel->update(array_except(Input::all(), '_method'));

            return json_encode(array('status' => 'oke'));
        }
    }

    public function sort($survey)
    {
        $positions = Input::get('positions');

        //remove 'panel-' prefix
        $positions = array_map(function ($item) {
            return str_replace('panel-', '', $item);
        }, $positions);

        if ($positions) {
            $panels = $this->panel->whereIn('id', $positions)->get();

            foreach ($panels as $panel) {
                $key = array_search($panel->id, $positions);

                $panel->panel_weight = $key * 10;

                $panel->save();
            }
        }

        return json_encode(array('status' => 'oke'));
    }
}