<?php

class RapportController extends AdminController{

    protected $search;

    function __construct(Search\SearchServiceInterface $search)
    {
        $this->search = $search;

        $this->beforeFilter('auth.admin');
    }

    public function getIndex()
    {
        $this->layout->content = View::make('rapport.index');
    }

} 