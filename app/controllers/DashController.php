<?php

class DashController extends AdminController{

    function __construct()
    {
        $this->beforeFilter('auth');
    }


    public function getIndex()
    {
        $message = Session::has('message') ? Session::get('message') : null;

        $error = Session::has('error') ? Session::get('error') : null;

        $this->layout->content = View::make('dash.index', compact(array('message', 'error')));
    }

}