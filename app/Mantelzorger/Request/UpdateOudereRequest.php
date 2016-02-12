<?php

/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 01/07/15
 * Time: 22:02.
 */
namespace App\Mantelzorger\Request;

use App\Http\Requests\Request;
use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;

class UpdateOudereRequest extends Request
{
    public function rules(Oudere $oudere, Mantelzorger $mantelzorger)
    {
        return $oudere->rules([], [
            'oudere' => $this->route()->parameter('oudere')->id,
            'mantelzorger' => $this->route()->parameter('mantelzorger')->id,
        ], ['oudere_id', 'mantelzorger_id']);
    }

    public function authorize()
    {
        return true;
    }
}
