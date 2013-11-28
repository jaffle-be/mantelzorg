<?php

namespace Instelling;

use Mantelzorger\Mantelzorger;
use View;
use Redirect;
use Input;

class MantelzorgerController extends \AdminController{

    protected $mantelzorger;

    public function __construct(Mantelzorger $mantelzorger)
    {
        $this->mantelzorger = $mantelzorger;

        $this->page = 'mantelzorger';

        $this->beforeFilter('auth');
        $this->beforeFilter('csrf', array('only' => array('store')));
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
        $validator = $this->mantelzorger->validator();

        if($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator->messages());
        }

        $mantelzorger = $this->mantelzorger->create(Input::except('_token'));

        return Redirect::route('mantelzorgers.index');
    }

} 