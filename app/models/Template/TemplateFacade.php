<?php

namespace Template;

use Illuminate\Support\Facades\Facade;

class TemplateFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'template';
    }
}