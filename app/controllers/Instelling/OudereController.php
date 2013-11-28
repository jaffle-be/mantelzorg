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



} 