<?php

namespace App\Http\Controllers;

use App\Beta\Registration;
use App\User;
use Auth;
use Hash;
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

    public function postIndex()
    {
        $validator = $this->registration->validator();

        if ($validator->fails()) {
            return Redirect::to('/')->with('errors', $validator->messages())->withInput();
        } else {
            $this->registration->create(Input::all());

            return Redirect::to('/')->with('message', true);
        }
    }

    public function getLogin()
    {
        return view('login');
    }

    public function postLogin()
    {
        $credentials = array(
            'email'    => Input::get('email'),
            'password' => Input::get('password'),
            'active'   => true
        );

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return Redirect::route('dash');
        } else {
            $user = $this->user->whereEmail(Input::get('email'))->first();

            if (!$user) {
                $error = Lang::get('master.errors.auth-email');
            } else {
                if ($user->active === '0') {
                    $error = Lang::get('master.errors.auth-active');
                } else {
                    $error = Lang::get('master.errors.auth-password');
                }
            }

            return Redirect::route('login')->withError($error);
        }
    }

    public function getLogout()
    {
        Auth::logout();

        return Redirect::route('home');
    }

    public function getReminder()
    {
        return view('reminder');
    }

    public function postReminder()
    {
        $credentials = array('email' => Input::get('email'));

        $message = Password::remind($credentials, function ($message, $user) {
            $message->subject(Lang::get('reminders.email.title'));
            $message->from('thomas@jaffle.be', 'Thomas Warlop');
        });

        return Redirect::back()->withMessage(Lang::get($message));
    }

    public function getReset($token)
    {
        return view('reset')->withToken($token);
    }

    public function postReset()
    {
        $credentials = array(
            'email'                 => Input::get('email'),
            'password'              => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
            'token'                 => Input::get('token')
        );

        $message = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);

            $user->save();
        });

        return Redirect::back()->withMessage(Lang::get($message));
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