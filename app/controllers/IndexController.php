<?php

use \Beta\Registration;

class IndexController extends BaseController {

    protected $layout = 'layout.front.master';

    /**
     * @var Beta\Registration
     */
    protected $registration;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
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
            'password' => Input::get('password')
        );

        Auth::attempt($credentials);
    }

}