<?php
namespace Api;

use Lang;

class MantelzorgerController extends \AdminController{

    public function ouderen($mantelzorger)
    {
        return json_encode(array(
            'status' => 'oke',
            'ouderen' => array('select' => Lang::get('instrument.kies_oudere')) + $mantelzorger->oudere->sortBy(function($item){
                    return $item->fullname;
                })->lists('fullname', 'id')
        ));
    }

} 