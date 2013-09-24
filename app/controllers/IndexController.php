<?php

use \Beta\Registration;

class IndexController extends BaseController {

    /**
     * @var Beta\Registration
     */
    protected $registration;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

	protected $layout = 'layout.front.master';

	public function getIndex()
	{
		$this->layout->content = View::make('beta');

        Admin::create(array(
            'firstname' => 'thomas',
            'lastname' => 'warlop',
            'password' => Hash::make('Ki5m6gf'),
            'email' => 'thomas.warlop@gmail.com',
            'active' => true,
        ));

        Admin::create(array(
            'firstname' => 'Ruud',
            'lastname' => 'Vanderheyden',
            'password' => Hash::make('Ki5m6gf'),
            'email' => 'ruudvanderheydenp@gmail.com',
            'active' => true,
        ));
	}

    public function postIndex()
    {
        $validator = $this->registration->validator();

        if($validator->fails())
        {
            return Redirect::back()->with('errors', $validator->messages());
        }
        else{
            $this->registration->create(Input::all());
            return Redirect::back()->with('success', true);
        }
    }

}