<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class Lock
{
    protected $route;

    protected $auth;

    public function __construct(Route $route, Guard $auth)
    {
        $this->route = $route;
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $this->auth->user();

        //if the user is an admin, we skip all checks
        if ($user->admin == 1) {
            return $next($request);
        }

        //needs better or correcter implementation!
        //i believe this currently has bugs to mantelzorger or oudere data

        $hulpverlener = $this->route->getParameter('hulpverlener');

        if ($hulpverlener) {
            $user = $this->auth->user();

            if ($user->admin == 0 && $user->id != $hulpverlener->id) {
                return redirect()->route('instellingen.index');
            }
        }

//        $mantelzorger = $this->route->getParameter('mantelzorger');
//
//        if($mantelzorger)
//        {
//            $user->hulpverleners =
//        }

        return $next($request);
    }
}
