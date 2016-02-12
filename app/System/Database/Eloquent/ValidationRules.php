<?php

namespace App\System\Database\Eloquent;

trait ValidationRules
{
    public function rules(array $rules = [], array $placeholders = [], array $except = [])
    {
        //the except rule is at the moment of implementation solely done to exclude
        //certain rules which were necessary in the first implementation of validation.
        //when we were still at laravel 4.0 when validation
        //was done in the controller using Validator::make()
        if (empty($rules)) {
            $rules = static::$rules;
        } else {
            $rules = array_intersect_key(static::$rules, array_flip($rules));
        }

        array_walk($rules, function (&$rule) use ($placeholders) {
            foreach ($placeholders as $placeholder => $value) {
                $rule = str_replace('#'.$placeholder, $value, $rule);
            }
        });

        //replace any pattern that's not been changed yet.
        array_walk($rules, function (&$rule) {
            $rule = preg_replace('/#[^,]+/', 'NULL', $rule);
        });

        return array_except($rules, $except);
    }
}
