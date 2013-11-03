<?php

class RapportController extends AdminController{

    public function getIndex()
    {
        $this->layout->content = View::make('rapport.index');
    }

} 