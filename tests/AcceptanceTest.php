<?php

use App\User;
use Integrated\AnnotationReader;
use Integrated\DatabaseTransactions;
use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application;
use Laracasts\TestDummy\Factory;

abstract class AcceptanceTest extends Selenium
{

    use Application;
    use DatabaseTransactions;
    use TestingTrait;

    protected $baseUrl;

    /**
     * We want to use our own AnnotationReader
     * it will allow us to sort different annotation actions
     *
     * @return AnnotationReader
     */
    protected function annotations()
    {
        if (! $this->annotations) {
            $this->annotations = new AnnotationReader($this);
        }

        return $this->annotations;
    }

    /**
     * @setUp
     */
    public function login()
    {
        $user = Factory::create('admin', ['password' => Hash::make('password')]);

        $this->visit(route('login'))
            ->submitForm('Aanmelden', ['email' => $user->email, 'password' => 'password']);
    }

    /**
     * @tearDown
     */
    public function logoutUser()
    {
        User::whereNotNull('id')->delete();
    }

    /**
     * @setUp
     */
    protected function setBaseUrl()
    {
        $this->baseUrl = env('APP_URL');
    }

    protected function open($uri)
    {
        if (!$this->session) {
            throw new Exception('Need to call visit before using the open method. as we need an open browser');
        }

        $this->session->open($uri);

        $this->updateCurrentUrl();

        return $this;
    }

}