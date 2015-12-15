<?php

namespace App\Mantelzorger\Request;

use App\Http\Requests\Request;
use App\Mantelzorger\Mantelzorger;

class NewMantelzorgerRequest extends Request
{
    public function rules(Mantelzorger $mantelzorger)
    {
        $hulpverlener = $this->route()->parameter('hulpverlener');

        return $mantelzorger->rules([], ['hulpverlener' => $hulpverlener->id], ['hulpverlener_id']);
    }

    public function authorize()
    {
        return true;
    }
}
