<?php

namespace Instelling;

use Mantelzorger\Oudere;
use View;
use Input;
use Redirect;

class OudereController extends \AdminController{

    /**
     * @var \Mantelzorger\Oudere
     */
    protected $oudere;

    public function __construct(Oudere $oudere)
    {
        $this->oudere = $oudere;

        $this->beforeFilter('auth');
        $this->beforeFilter('ouderen');
    }

    public function create($mantelzorger)
    {
        $this->layout->content = View::make('instellingen.ouderen.create', compact(array('mantelzorger')));
    }

    public function store($mantelzorger)
    {
        $input = Input::except('_token');

        $input['mantelzorger_id'] = $mantelzorger->id;

        $validator = $this->oudere->validator($input);

        if($validator->fails())
        {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        }
        else
        {

            $this->oudere->create($input);

            return Redirect::action('Instelling\MantelzorgerController@index', $mantelzorger->hulpverlener->id);
        }

    }

    public function edit($mantelzorger, $oudere)
    {
        $oudere = $this->oudere->find($oudere);

        if($oudere)
        {
            $this->layout->content = View::make('instellingen.ouderen.edit', compact(array('mantelzorger', 'oudere')));
        }
        else
        {
            return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', array($mantelzorger->hulpverlener_id));
        }
    }

    public function update($mantelzorger, $oudere)
    {
        $oudere = $this->oudere->find($oudere);

        if($oudere)
        {
            $validator = $this->oudere->validator(array(), array('firstname', 'lastname', 'birthday', 'male', 'street', 'postal', 'city', 'phone'));

            $validator->sometimes('email', 'email|unique:ouderen,email', function($input) use ($oudere){
                return $input->email !== $oudere->email;
            });

            if($validator->fails())
            {
                return redirect::back()->withInput()->withErrors($validator->messages());
            }
            else
            {
                $oudere->update(Input::except('_token'));
            }
        }

        return Redirect::route('instellingen.{hulpverlener}.mantelzorgers.index', $mantelzorger->hulpverlener_id);
    }



} 