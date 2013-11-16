<?php

class InstellingController extends AdminController{

    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function getIndex()
    {
        $this->layout->content = View::make('instellingen.index');
    }
} 