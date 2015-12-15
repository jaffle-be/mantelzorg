<?php

namespace App\Mantelzorger;

trait MantelzorgerSkills
{
    public function listOudereByName()
    {
        return $this->oudere->sortBy(function ($item) {
            return $item->displayName;
        });
    }
}
