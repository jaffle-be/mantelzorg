<?php

namespace Instelling;

use Mantelzorger\Mantelzorger;
use View;
use Redirect;
use Input;
use Auth;

class MantelzorgerController extends \AdminController{

    protected $mantelzorger;

    public function __construct(Mantelzorger $mantelzorger)
    {
        $this->mantelzorger = $mantelzorger;

        $this->page = 'mantelzorger';

        $this->beforeFilter('auth');
        $this->beforeFilter('csrf', array('only' => array('store')));
        $this->beforeFilter('mantelzorgers');
    }

    public function index($hulpverlener)
    {
        $this->layout->content = View::make('instellingen.mantelzorgers.index', compact(array('hulpverlener')))
            ->nest('subnav', 'layout.admin.subnavs.instellingen', array('page' => $this->page));
    }

    public function create($hulpverlener)
    {
        $this->layout->content = View::make('instellingen.mantelzorgers.create', compact(array('hulpverlener')))
            ->nest('subnav', 'layout.admin.subnavs.instellingen', array('page' => $this->page));
    }

    public function store($hulpverlener)
    {
        $input = Input::except('_token');

        $input['hulpverlener_id'] = $hulpverlener->id;

        $validator = $this->mantelzorger->validator($input);

        if($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator->messages());
        }

        $mantelzorger = $this->mantelzorger->create($input);

        return Redirect::action('Instelling\MantelzorgerController@index', array($hulpverlener->id));
    }

    public function edit($hulpverlener, $mantelzorger)
    {
        $mantelzorger = $this->mantelzorger->find($mantelzorger);

        if($mantelzorger)
        {
            $this->layout->content = View::make('instellingen.mantelzorgers.edit', compact(array('hulpverlener', 'mantelzorger')));
        }
    }

    public function update($hulpverlener)
    {
        $input = Input::except('_token');

        $input['mantelzorger_id'] = $hulpverlener->mantelzorger_id;

        $validator = $this->mantelzorger->validator($input);

        if($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator->messages());
        }

        else
        {
            $mantelzorger = $this->mantelzorger->find($hulpverlener->mantelzorger_id);

            $mantelzorger->update($input);

            $mantelzorger->save();

            return Redirect::action('Instelling\MantelzorgerController@index', $hulpverlener->id);
        }
    }

    public function delete()
    {

    }

} 