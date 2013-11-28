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

    public function index()
    {
        $this->layout->content = View::make('instellingen.mantelzorgers')
            ->nest('subnav', 'layout.admin.subnavs.instellingen', array('page' => $this->page));
    }

    public function store()
    {
    }

} 