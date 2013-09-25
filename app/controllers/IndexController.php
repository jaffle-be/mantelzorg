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

    public function getInstrument()
    {
        $this->layout->content = View::make('instrument');
    }

    public function getTeam()
    {
        $this->layout->content = View::make('team');
    }

}