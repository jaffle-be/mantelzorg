<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 01/07/15
 * Time: 22:02
 */

namespace App\Mantelzorger\Request;


use App\Http\Requests\Request;
use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;

class UpdateOudereRequest extends Request{

    public function rules(Oudere $oudere, Mantelzorger $mantelzorger)
    {
        return $oudere->rules([], [
            'oudere'       => $oudere->id,
            'mantelzorger' => $mantelzorger->id
        ]);
    }

    public function authorize()
    {
        return true;
    }

}