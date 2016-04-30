<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as RootController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends RootController
{
    use AuthorizesRequests, ValidatesRequests;
}
