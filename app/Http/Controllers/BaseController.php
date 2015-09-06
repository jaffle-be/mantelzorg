<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use DispatchesCommands;

//    /**
//     * Setup the layout used by the controller.
//     *
//     * @return void
//     */
//    protected function setupLayout()
//    {
//        if (!is_null($this->layout)) {
//            $this->layout = view($this->layout);
//        }
//    }
}