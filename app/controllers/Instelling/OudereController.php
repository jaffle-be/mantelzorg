<?php

namespace Instelling;

use Mantelzorger\Oudere;
use View;
use Input;
use Redirect;
use Meta\Context;
use Meta\Value;
use Lang;

class OudereController extends \AdminController
{

    /**
     * @var \Mantelzorger\Oudere
     */
    protected $oudere;

    /**
     * @var \Meta\Context
     */
    protected $metaContext;

    /**
     * @var \Meta\Value
     */
    protected $metaValue;

    public function __construct(Oudere $oudere, Context $context, Value $value)
    {
        $this->oudere = $oudere;

        $this->metaContext = $context;

        $this->metaValue = $value;

        $this->beforeFilter('auth');

        $this->beforeFilter('ouderen');
    }

    public function create($mantelzorger)
    {
        $relations_mantelzorger = $this->getRelationsMantelzorger();

        $woonsituaties = $this->getWoonsituaties();

        $this->layout->content = View::make('instellingen.ouderen.create', compact('mantelzorger', 'relations_mantelzorger', 'woonsituaties'));
    }

    public function store($mantelzorger)
    {
        $input = Input::except('_token');

        $input['mantelzorger_id'] = $mantelzorger->id;

        $input = $this->processValue($input);

        $validator = $this->oudere->validator($input, [], [
            'mantelzorger' => $mantelzorger->id
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        } else {

            $this->oudere->create($input);

            return Redirect::action('Instelling\MantelzorgerController@index', $mantelzorger->hulpverlener->id);
        }
    }

    public function edit($mantelzorger, $oudere)
    {
        $oudere = $this->oudere->find($oudere);

        if ($oudere) {
            $relations_mantelzorger = $this->getRelationsMantelzorger();

            $woonsituaties = $this->getWoonsituaties();

            $this->layout->content = View::make('instellingen.ouderen.edit', compact('mantelzorger', 'oudere', 'relations_mantelzorger', 'woonsituaties'));
        } else {
            return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', array($mantelzorger->hulpverlener_id));
        }
    }

    public function update($mantelzorger, $oudere)
    {
        $oudere = $this->oudere->find($oudere);

        if ($oudere) {

            $input = $this->processValue(Input::except('_token'));

            $input['mantelzorger_id'] = $mantelzorger->id;

            $validator = $this->oudere->validator($input, [], [
                'oudere' => $oudere->id,
                'mantelzorger' => $mantelzorger->id
            ]);

            if ($validator->fails()) {
                return redirect::back()->withInput()->withErrors($validator->messages());
            } else {
                $oudere->update($input);
            }
        }

        return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', $mantelzorger->hulpverlener_id);
    }

    protected function getRelationsMantelzorger()
    {
        $relations_mantelzorger = $this->metaContext->with('values')->where('context', Context::RELATION_MANTELZORGER_OUDERE)->first()->values->lists('value', 'id');

        //append an empty option
        return array('' => Lang::get('users.relatie_mantelzorger')) + $relations_mantelzorger + array('*new*' => Lang::get('users.relatie_mantelzorger_alternate'));
    }

    protected function processValue($input)
    {
        $context = $this->metaContext->where('context', Context::RELATION_MANTELZORGER_OUDERE)->first();

        //find the meta value by the id, if none exists... we need to create it
        $value = $this->metaValue->find($input['mantelzorger_relation']);

        if (!$value) {
            //if both are not empty, we create a value using the alternate
            if ($input['mantelzorger_relation'] == '*new*' && $input['mantelzorger_relation_alternate']) {
                $value = $this->metaValue->create(array(
                    'context_id' => $context->id,
                    'value'      => $input['mantelzorger_relation_alternate']
                ));
            }
        }

        $input['mantelzorger_relation'] = $value ? $value->id : null;

        //always unset alternate, by now relation_oudere has the correct value for the mantelzorger model
        unset($input['mantelzorger_relation_alternate']);

        return $input;
    }

    protected function getWoonsituaties()
    {
        $values = $this->metaContext->with(['values'])->where('context', Context::OUDEREN_WOONSITUATIE)->first()->values->lists('value', 'id');

        return array('' => Lang::get('users.pick_woonsituatie')) + $values;
    }
}