<?php
namespace App\Http\Controllers\Api;

use Lang;

class MantelzorgerController extends \App\Http\Controllers\AdminController
{

    public function ouderen($mantelzorger)
    {
        return json_encode(array(
            'status'  => 'oke',
            'ouderen' => array('select' => Lang::get('instrument.kies_oudere')) + $mantelzorger->oudere->sortBy(function ($item) {
                    return $item->displayName;
                })->lists('displayName', 'id')
        ));
    }
}