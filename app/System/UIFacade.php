<?php

namespace App\System;

use Illuminate\Support\Facades\Facade;

class UIFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ui';
    }
}
