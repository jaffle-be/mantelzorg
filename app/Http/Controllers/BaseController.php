<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use DispatchesJobs;

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