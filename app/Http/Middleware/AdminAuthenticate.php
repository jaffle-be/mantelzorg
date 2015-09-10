<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Lang;

class AdminAuthenticate
{

    /**
     * @var Guard
     */
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->auth->guest()) {

            if($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else{
                return redirect()->route('login')->with('error', Lang::get('master.info.not-logged-in'));
            }
        }

        $user = $this->auth->user();

        if (!$user->admin) {
            return redirect()->route('dash')->with('message', Lang::get('master.info.no-right-to-section'));
        }

        return $next($request);
    }
}