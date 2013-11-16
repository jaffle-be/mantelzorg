<?php

namespace Instelling;

use View;

class MantelzorgerController extends \AdminController{

    public function __construct()
    {
        $this->page = 'mantelzorger';

        $this->beforeFilter('auth');
    }

    public function index()
    {
        $this->layout->content = View::make('instellingen.mantelzorgers')
            ->nest('subnav', 'layout.admin.subnavs.instellingen', array('page' => $this->page));
    }

} 