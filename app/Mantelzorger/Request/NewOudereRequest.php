<?php namespace App\Mantelzorger\Request;

use App\Http\Requests\Request;
use App\Mantelzorger\Oudere;

class NewOudereRequest extends Request
{

    public function rules(Oudere $oudere)
    {
        return $oudere->rules([], [], ['mantelzorger_id']);
    }

    public function authorize()
    {
        return true;
    }

}