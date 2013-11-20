<?php

use \Beta\Registration;

class IndexController extends BaseController {

    protected $layout = 'layout.front.master';

    /**
     * @var Beta\Registration
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
		$this->layout->content = View::make('beta', array('user'));
	}

    public function postIndex()
    {
        $validator = $this->registration->validator();

        if($validator->fails())
        {
            return Redirect::to('/')->with('errors', $validator->messages())->withInput();
        }
        else{
            $this->registration->create(Input::all());
            return Redirect::to('/')->with('message', true);
        }
    }

    public function getHetInstrument()
    {
        $this->layout->content = View::make('instrument');
    }

    public function getHetTeam()
    {
        $this->layout->content = View::make('team');
    }

    public function getLogin()
    {
        $error = Session::has('error') ? Session::get('error') : null;

        $this->layout->content = View::make('login', compact(array('error')));
    }

    public function postLogin()
    {
        $credentials = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'active' => true
        );

        if(Auth::attempt($credentials))
        {
            $user = Auth::user();

            return Redirect::action('DashController@getIndex');
        }
        else
        {
            $user = $this->user->whereEmail(Input::get('email'))->first();

            if(!$user)
            {
                $error = Lang::get('master.errors.auth-email');
            }
            else
            {
                if($user->active === '0')
                {
                    $error = Lang::get('master.errors.auth-active');
                }
                else
                {
                    $error = Lang::get('master.errors.auth-password');
                }
            }

            return Redirect::action('IndexController@getLogin')->withError($error);
        }
    }

    public function getLogout()
    {
        Auth::logout();

        return Redirect::action('IndexController@getIndex');
    }

}