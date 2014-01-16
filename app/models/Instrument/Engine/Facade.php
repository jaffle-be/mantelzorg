<?php

namespace Instrument\Engine;

class Facade extends \Illuminate\Support\Facades\Facade{

    public static function getFacadeAccessor()
    {
        return 'instrument.engine';
    }

} 