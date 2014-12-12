<?php

class RapportController extends AdminController{

    function __construct()
    {
        $this->beforeFilter('auth.admin');
    }


    public function getIndex()
    {
        $this->layout->content = View::make('rapport.index');
    }

} 