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
		$this->layout->content = View::make('beta');
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
        $this->layout->content = View::make('login');
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

            return Redirect::route('dash');
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

    public function getReminder()
    {
        $this->layout->content = View::make('reminder');
    }

    public function postReminder()
    {
        $credentials = array('email' => Input::get('email'));

        $message = Password::remind($credentials, function($message, $user){
            $message->subject(Lang::get('reminders.email.title'));
            $message->from('thomas@jaffle.be', 'Thomas Warlop');
        });

        return Redirect::back()->withMessage(Lang::get($message));
    }

    public function getReset($token)
    {
        $this->layout->content = View::make('reset')->withToken($token);
    }

    public function postReset()
    {
        $credentials = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation')
        );

        return Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);

            $user->save();

            return Redirect::action('IndexController@getIndex');
        });
    }

}