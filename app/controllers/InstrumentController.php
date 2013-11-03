<?php

class InstrumentController extends AdminController{

    public function getIndex()
    {
        $this->layout->content = View::make('instrument.index');
    }

} 