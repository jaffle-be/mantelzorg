<?php

class DashController extends AdminController{

    public function getIndex()
    {
        $this->layout->content = View::make('dash.index');
    }

} 