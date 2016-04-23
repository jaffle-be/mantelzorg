<?php

namespace App\Http\Controllers\Instelling;

use App\Mantelzorger\Commands\NewOudere;
use App\Mantelzorger\Commands\UpdateOudere;
use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;
use App\Mantelzorger\Request\NewOudereRequest;
use App\Mantelzorger\Request\UpdateOudereRequest;
use App\Meta\Context;
use App\Meta\Value;
use Input;
use Lang;
use Redirect;

class OudereController extends \App\Http\Controllers\AdminController
{
    /**
     * @var \App\Mantelzorger\Oudere
     */
    protected $oudere;

    /**
     * @var \App\Meta\Context
     */
    protected $metaContext;

    /**
     * @var \App\Meta\Value
     */
    protected $metaValue;

    public function __construct(Oudere $oudere, Context $context, Value $value)
    {
        $this->oudere = $oudere;

        $this->metaContext = $context;

        $this->metaValue = $value;

        $this->middleware('auth');

        $this->middleware('lock');
    }

    public function create($mantelzorger)
    {
        //this should be a trait loaded onto the model.
        //the trait should be a generic one placed into to the meta module namespace.
        //this would allow easy reuse of the meta component.
        //this is easily something which can be reused in any project.
        $relations_mantelzorger = $this->getRelationsMantelzorger();

        $woonsituaties = $this->getWoonsituaties();

        $hulpbehoeftes = $this->getHulpbehoeftes();

        $belprofielen = $this->getBelprofielen();

        return view('instellingen.ouderen.create', compact('mantelzorger', 'relations_mantelzorger', 'woonsituaties', 'hulpbehoeftes', 'belprofielen'));
    }

    public function store(Mantelzorger $mantelzorger, NewOudereRequest $request)
    {
        $input = $this->processValue($request->except('_token'), Context::MANTELZORGER_RELATION);

        $this->dispatchFromArray(NewOudere::class, [
            'mantelzorger' => $mantelzorger,
            'input' => $input,
        ]);

        return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', $mantelzorger->hulpverlener->id);
    }

    public function edit($mantelzorger, $oudere)
    {
        if ($oudere) {
            $relations_mantelzorger = $this->getRelationsMantelzorger();

            $woonsituaties = $this->getWoonsituaties();

            $hulpbehoeftes = $this->getHulpbehoeftes();

            $belprofielen = $this->getBelprofielen();

            return view('instellingen.ouderen.edit', compact('mantelzorger', 'oudere', 'relations_mantelzorger', 'woonsituaties', 'hulpbehoeftes', 'belprofielen'));
        } else {
            return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', array($mantelzorger->hulpverlener_id));
        }
    }

    public function update(UpdateOudereRequest $request, Mantelzorger $mantelzorger, Oudere $oudere)
    {
        $input = $this->processValue($request->except('_token'), Context::MANTELZORGER_RELATION);

        $input = array_except($input, ['_method']);

        $status = $this->dispatchFromArray(UpdateOudere::class, ['mantelzorger' => $mantelzorger, 'oudere' => $oudere, 'input' => $input]);

        return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', $mantelzorger->hulpverlener_id);
    }

    protected function getRelationsMantelzorger()
    {
        $relations_mantelzorger = $this->metaContext->with('values')->where('context', Context::MANTELZORGER_RELATION)->first()->values->pluck('value', 'id')->all();

        //append an empty option
        return array('' => Lang::get('users.relatie_mantelzorger')) + $relations_mantelzorger + array('*new*' => Lang::get('users.relatie_mantelzorger_alternate'));
    }

    protected function processValue(array $input, $context)
    {
        $context = $this->metaContext->where('context', $context)->first();

        //find the meta value by the id, if none exists... we need to create it
        $value = $this->metaValue->find($input[$context->context]);

        $alternate = $context->context.'_alternate';

        if (!$value) {

            //need to create a new one? -> check for existing, or create.
            if ($input[$context->context] == '*new*' && $input[$alternate]) {
                $value = $this->metaValue->where('context_id', $context->id)
                    ->where('value', $input[$alternate])->first();

                if (!$value) {
                    $value = $this->metaValue->create(array(
                        'context_id' => $context->id,
                        'value' => $input[$alternate],
                    ));
                }
            }
        }

        $input[$context->context] = $value ? $value->id : null;

        //always unset alternate, by now context value has the correct value for the model
        unset($input[$alternate]);

        return $input;
    }

    protected function getMeta($context)
    {
        return $this->metaContext->with(['values'])->where('context', $context)->first()->values->pluck('value', 'id')->all();
    }

    protected function getWoonsituaties()
    {
        $values = $this->getMeta(Context::OUDEREN_WOONSITUATIE);

        return array('' => Lang::get('users.pick_woonsituatie')) + $values;
    }

    protected function getHulpbehoeftes()
    {
        $values = $this->getMeta(Context::OORZAAK_HULPBEHOEFTE);

        return array('' => Lang::get('users.pick_oorzaak_hulpbehoefte')) + $values;
    }

    protected function getBelprofielen()
    {
        $values = $this->getMeta(Context::BEL_PROFIEL);

        return array('' => Lang::get('users.pick_bel_profiel')) + $values;
    }
}
