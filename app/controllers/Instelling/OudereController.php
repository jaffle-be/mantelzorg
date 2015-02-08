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

        $hulpbehoeftes = $this->getHulpbehoeftes();

        $this->layout->content = View::make('instellingen.ouderen.create', compact('mantelzorger', 'relations_mantelzorger', 'woonsituaties', 'hulpbehoeftes'));
    }

    public function store($mantelzorger)
    {
        $input = Input::except('_token');

        $input['mantelzorger_id'] = $mantelzorger->id;

        $input = $this->processValue($input, Context::MANTELZORGER_RELATION);
        $input = $this->processValue($input, Context::OORZAAK_HULPBEHOEFTE);

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

            $hulpbehoeftes = $this->getHulpbehoeftes();

            $this->layout->content = View::make('instellingen.ouderen.edit', compact('mantelzorger', 'oudere', 'relations_mantelzorger', 'woonsituaties', 'hulpbehoeftes'));
        } else {
            return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', array($mantelzorger->hulpverlener_id));
        }
    }

    public function update($mantelzorger, $oudere)
    {
        $oudere = $this->oudere->find($oudere);

        if ($oudere) {

            $input = Input::except('_token');

            $input['mantelzorger_id'] = $mantelzorger->id;

            $input = $this->processValue($input, Context::MANTELZORGER_RELATION);
            $input = $this->processValue($input, Context::OORZAAK_HULPBEHOEFTE);

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
        $relations_mantelzorger = $this->metaContext->with('values')->where('context', Context::MANTELZORGER_RELATION)->first()->values->lists('value', 'id');

        //append an empty option
        return array('' => Lang::get('users.relatie_mantelzorger')) + $relations_mantelzorger + array('*new*' => Lang::get('users.relatie_mantelzorger_alternate'));
    }

    protected function processValue(array $input, $context)
    {
        $context = $this->metaContext->where('context', $context)->first();

        //find the meta value by the id, if none exists... we need to create it
        $value = $this->metaValue->find($input[$context->context]);

        $alternate = $context->context . '_alternate';

        if (!$value) {

            //need to create a new one? -> check for existing, or create.
            if ($input[$context->context] == '*new*' && $input[$alternate]) {

                $value = $this->metaValue->where('context_id', $context->id)
                    ->where('value', $input[$alternate])->first();

                if(!$value)
                {
                    $value = $this->metaValue->create(array(
                        'context_id' => $context->id,
                        'value'      => $input[$alternate]
                    ));
                }
            }
        }

        $input[$context->context] = $value ? $value->id : null;

        //always unset alternate, by now context value has the correct value for the model
        unset($input[$alternate]);

        return $input;
    }

    protected function getWoonsituaties()
    {
        $values = $this->metaContext->with(['values'])->where('context', Context::OUDEREN_WOONSITUATIE)->first()->values->lists('value', 'id');

        return array('' => Lang::get('users.pick_woonsituatie')) + $values;
    }

    private function getHulpbehoeftes()
    {
        $values = $this->metaContext->with(['values'])->where('context', Context::OORZAAK_HULPBEHOEFTE)->first()->values->lists('value', 'id');

        return array('' => Lang::get('users.pick_oorzaak_hulpbehoefte')) + $values + array('*new*' => Lang::get('users.oorzaak_hulpbehoefte_alternate'));
    }
}