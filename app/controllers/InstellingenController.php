<?php

class InstellingenController extends AdminController{

    public function getIndex()
    {
        $this->layout->content = View::make('instellingen.index');
    }
} 