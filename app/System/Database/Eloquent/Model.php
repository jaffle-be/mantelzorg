<?php namespace App\System\Database\Eloquent;

class Model extends \Illuminate\Database\Eloquent\Model
{

    public static $getMutatorCache = [];

    public function hasGetMutator($key)
    {
        if (!isset(static::$getMutatorCache[$key])) {
            static::$getMutatorCache[$key] = parent::hasGetMutator($key);
        }

        return static::$getMutatorCache[$key];
    }
}