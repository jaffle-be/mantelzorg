<?php namespace App;

use Carbon\Carbon;

trait HandleBirthdayTrait
{
    public function setBirthdayAttribute($value)
    {
        if (!empty($value)) {

            if(is_string($value))
            {
                $value = preg_replace('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', '$3-$2-$1', $value);

                $this->attributes['birthday'] = $value;
            }
            else if(is_object($value) && $value instanceof \DateTime)
            {
                $this->attributes['birthday'] = $value->format('Y-m-d');
            }
            else{
                throw new \InvalidArgumentException('unable to set birthday due to bad value');
            }
        }
    }

}