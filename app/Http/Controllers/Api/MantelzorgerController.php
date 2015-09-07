<?php
namespace App\Http\Controllers\Api;

use Lang;

class MantelzorgerController extends \App\Http\Controllers\AdminController
{

    public function ouderen($mantelzorger)
    {
        return json_encode(array(
            'status'  => 'oke',
            'ouderen' => array('select' => Lang::get('instrument.kies_oudere')) + $mantelzorger->oudere()->byName()->get()->lists('displayName', 'id')
        ));
    }
}