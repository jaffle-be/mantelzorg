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
            return Redirect::back()->with('errors', $validator->messages());
        }
        else{
            $this->registration->create(Input::all());
            return Redirect::back()->with('message', true);
        }
    }

}