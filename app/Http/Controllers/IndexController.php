<?php

namespace App\Http\Controllers;

use App\Beta\Registration;
use App\User;
use Auth;
use Hash;
use Illuminate\Contracts\Validation\Factory;
use Input;
use Lang;
use Password;
use Redirect;
use Session;

class IndexController extends BaseController
{
    protected $layout = 'layout.front.master';

    /**
     * @var \App\Beta\Registration
     */
    protected $registration;

    /**
     * @var User
     */
    protected $user;

    public function __construct(Registration $registration, User $user)
    {
        $this->registration = $registration;

        $this->user = $user;
    }

    public function getIndex()
    {
        return view('beta');
    }

    public function postIndex(Factory $validator)
    {
        $validator = $validator->make(Input::all(), $this->registration->rules());

        if ($validator->fails()) {
            return Redirect::to('/')->with('errors', $validator->messages())->withInput();
        } else {
            $this->registration->create(Input::all());

            return Redirect::to('/')->with('message', true);
        }
    }

    public function getHijack($user)
    {
        $current = Auth::user();

        if ($current->admin) {
            $user = $this->user->find($user);

            if ($user) {
                Auth::login($user);

                Session::set('hijack-original', $current->id);

                return Redirect::route('dash');
            }
        }
    }

    public function getRejack()
    {
        $original = Session::get('hijack-original');

        if ($original) {
            $user = $this->user->find($original);

            if ($user->admin) {
                Auth::login($user);

                Session::forget('hijack-original');

                return Redirect::route('dash');
            }
        }
    }
}
