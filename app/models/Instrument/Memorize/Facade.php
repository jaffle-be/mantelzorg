<?php

namespace App\Instrument\Memorize;

class Facade extends \Illuminate\Support\Facades\Facade
{

    public static function getFacadeAccessor()
    {
        return 'instrument.memorize';
    }
}