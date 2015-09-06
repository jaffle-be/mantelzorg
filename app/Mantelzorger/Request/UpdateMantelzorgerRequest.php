<?php namespace App\Mantelzorger\Request;

use App\Http\Requests\Request;
use App\Mantelzorger\Mantelzorger;

class UpdateMantelzorgerRequest extends Request
{

    public function rules()
    {
        $mantelzorger = $this->route()->parameter('mantelzorgers');

        $hulpverlener = $this->route()->parameter('hulpverlener');

        return $mantelzorger->rules([], ['hulpverlener' => $hulpverlener->id, 'mantelzorger' => $mantelzorger->id], ['hulpverlener_id']);
    }

    public function authorize()
    {
        return true;
    }

}