<?php

namespace Instelling;

use Illuminate\Pagination\Paginator;
use Mantelzorger\Mantelzorger;
use View;
use Redirect;
use Input;
use Auth;

/**
 * Class MantelzorgerController
 *
 * @package Instelling
 */
class MantelzorgerController extends \AdminController
{

    /**
     * @var \Mantelzorger\Mantelzorger
     */
    protected $mantelzorger;

    /**
     * @param Mantelzorger $mantelzorger
     */
    public function __construct(Mantelzorger $mantelzorger)
    {
        $this->mantelzorger = $mantelzorger;

        $this->beforeFilter('auth');

        $this->beforeFilter('csrf', array('only' => array('store')));

        $this->beforeFilter('mantelzorgers');
    }

    public function index($hulpverlener)
    {
        $search = $this->mantelzorger->search();

        /** @var Paginator $mantelzorgers */
        $mantelzorgers = $search
            ->filterMatch('hulpverlener_id', $hulpverlener->id)
            ->whereMulti_match(['firstname', 'lastname', 'identifier', 'oudere.firstname', 'oudere.lastname', 'oudere.identifier'], Input::get('query'))
            ->orderBy('lastname', 'asc')
            ->orderBy('firstname', 'asc')
            ->get();

        $this->layout->content = View::make('instellingen.mantelzorgers.index', compact(array('hulpverlener', 'mantelzorgers')));
    }

    public function create($hulpverlener)
    {
        $this->layout->content = View::make('instellingen.mantelzorgers.create', compact(array('hulpverlener')));
    }

    public function store($hulpverlener)
    {
        $input = Input::except('_token');

        $input['hulpverlener_id'] = $hulpverlener->id;

        $validator = $this->mantelzorger->validator($input);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator->messages());
        }

        $mantelzorger = $this->mantelzorger->create($input);

        return Redirect::action('Instelling\MantelzorgerController@index', array($hulpverlener->id));
    }

    public function edit($hulpverlener, $mantelzorger)
    {
        $mantelzorger = $this->mantelzorger->find($mantelzorger);

        if ($mantelzorger) {
            $this->layout->content = View::make('instellingen.mantelzorgers.edit', compact(array('hulpverlener', 'mantelzorger')));
        }
    }

    public function update($hulpverlener, $mantelzorger)
    {
        $mantelzorger = $this->mantelzorger->find($mantelzorger);

        if ($mantelzorger) {
            $input = Input::except('_token');

            $input['mantelzorger_id'] = $mantelzorger->id;

            $validator = $this->mantelzorger->validator($input, array('firstname', 'lastname', 'birthday', 'male', 'street', 'postal', 'city', 'phone'));

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator->messages());
            } else {
                $mantelzorger->update($input);

                $mantelzorger->save();
            }
        }

        return Redirect::action('Instelling\MantelzorgerController@index', $hulpverlener->id);
    }

    public function delete()
    {
    }
}